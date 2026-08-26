<?php
namespace AnythingInside\Import;

use Exception;

/**
 * csv_parser.php — Native Enterprise CSV Parser & Utility
 *
 * Robust, zero-dependency CSV parser powered by native PHP functions:
 *  - Automatic delimiter detection (comma, semicolon, tab)
 *  - Automatic encoding detection & UTF-8 conversion (UTF-8, Windows-1252, ISO-8859-1, ASCII)
 *  - UTF-8 & UTF-16 BOM removal
 *  - Control character cleaning
 *  - Column header alias mapping & normalization
 */
class CsvParser
{
    /** Max allowed file size in bytes (10MB) */
    public const MAX_FILE_SIZE = 10485760;

    /**
     * Read and parse a CSV file.
     *
     * @param  string $filePath Absolute path to the CSV file
     * @return array<int, array<int, string>> Array of parsed rows
     * @throws Exception if file read fails
     */
    public static function parseCsv(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception('CSV file does not exist or is not readable.');
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new Exception('Failed to read CSV file contents.');
        }

        // 1. Strip BOM (UTF-8, UTF-16LE, UTF-16BE)
        $content = self::stripBOM($content);

        // 2. Detect & Convert Encoding to UTF-8
        $content = self::convertToUtf8($content);

        // 3. Auto-detect CSV Delimiter (comma, semicolon, tab)
        $delimiter = self::detectDelimiter($content);

        // 4. Stream & Parse via fgetcsv
        $handle = fopen('php://memory', 'r+');
        if ($handle === false) {
            throw new Exception('Failed to open memory stream for CSV parsing.');
        }

        fwrite($handle, $content);
        rewind($handle);

        $rows = [];
        while (($data = fgetcsv($handle, 10000, $delimiter)) !== false) {
            // Clean control characters and trim each cell
            $cleanedRow = array_map(function ($val) {
                $v = (string) ($val ?? '');
                $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v);
                return trim($v);
            }, $data);

            $rows[] = $cleanedRow;
        }

        fclose($handle);

        if (empty($rows)) {
            throw new Exception('The CSV file is empty.');
        }

        return $rows;
    }

    /**
     * Strip Byte Order Marks (BOM) from the beginning of string content.
     */
    public static function stripBOM(string $text): string
    {
        // UTF-8 BOM
        if (strncmp($text, "\xEF\xBB\xBF", 3) === 0) {
            return substr($text, 3);
        }
        // UTF-16 LE BOM
        if (strncmp($text, "\xFF\xFE", 2) === 0) {
            return substr($text, 2);
        }
        // UTF-16 BE BOM
        if (strncmp($text, "\xFE\xFF", 2) === 0) {
            return substr($text, 2);
        }
        return $text;
    }

    /**
     * Detect and convert text encoding to clean UTF-8.
     */
    public static function convertToUtf8(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Check if already valid UTF-8
        if (mb_check_encoding($text, 'UTF-8')) {
            return $text;
        }

        $detected = mb_detect_encoding($text, ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ASCII', 'CP1252'], true);
        if ($detected && $detected !== 'UTF-8') {
            $converted = mb_convert_encoding($text, 'UTF-8', $detected);
            if ($converted !== false) {
                return $converted;
            }
        }

        // Fallback iconv/mb_convert
        return @mb_convert_encoding($text, 'UTF-8', 'ISO-8859-1');
    }

    /**
     * Auto-detect the primary CSV field delimiter (comma, semicolon, tab).
     */
    public static function detectDelimiter(string $content): string
    {
        $delimiters = [',' => 0, ';' => 0, "\t" => 0];

        // Read up to first 10 lines for sampling
        $lines = explode("\n", $content, 10);
        $sampleLines = array_slice($lines, 0, 5);

        foreach ($sampleLines as $line) {
            foreach ($delimiters as $delim => $count) {
                $delimiters[$delim] += substr_count($line, $delim);
            }
        }

        // Find the delimiter with highest count
        arsort($delimiters);
        $chosen = key($delimiters);

        return ($delimiters[$chosen] > 0) ? $chosen : ',';
    }

    /**
     * Filter out empty rows from a 2D row array.
     */
    public static function removeEmptyRows(array $rows): array
    {
        return array_values(array_filter($rows, function (array $row): bool {
            return !empty(array_filter($row, fn($v) => trim((string) ($v ?? '')) !== ''));
        }));
    }

    /**
     * Normalize a header string for case-insensitive alias matching.
     */
    public static function normalizeHeader(string $header): string
    {
        $cleaned = preg_replace('/[\x00-\x1F\x7F]/u', '', $header);
        return strtolower(trim(preg_replace('/\s+/', ' ', $cleaned)));
    }

    /**
     * Map column indexes against an alias dictionary.
     *
     * @param  array<int, string> $headerRow The header row from file
     * @param  array<string, array<int, string>> $aliasMap Internal field => list of aliases
     * @return array{map: array<string, int>, extra: array<int, string>}
     */
    public static function mapColumns(array $headerRow, array $aliasMap): array
    {
        $colMap    = [];
        $extraCols = [];

        foreach ($headerRow as $colIndex => $rawHeader) {
            $normalized = self::normalizeHeader((string) ($rawHeader ?? ''));
            if ($normalized === '') {
                continue;
            }

            $matchedField = null;
            foreach ($aliasMap as $field => $aliases) {
                if (isset($colMap[$field])) {
                    continue;
                }
                foreach ($aliases as $alias) {
                    if ($normalized === self::normalizeHeader($alias)) {
                        $matchedField = $field;
                        break 2;
                    }
                }
            }

            if ($matchedField !== null) {
                $colMap[$matchedField] = $colIndex;
            } else {
                $extraCols[$colIndex] = trim((string) $rawHeader);
            }
        }

        return ['map' => $colMap, 'extra' => $extraCols];
    }

    /**
     * Find the index of the first non-numeric header column to use as fallback identifier.
     */
    public static function firstIdentifierColumn(array $headerRow): ?int
    {
        foreach ($headerRow as $index => $header) {
            $h = trim((string) ($header ?? ''));
            if ($h !== '' && !is_numeric($h)) {
                return $index;
            }
        }
        return null;
    }
}