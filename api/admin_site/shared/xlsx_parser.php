<?php
namespace AnythingInside\Import;

use Exception;

// Load SimpleXLSX if not loaded
if (!class_exists('AnythingInside\Import\SimpleXLSX')) {
    $file = __DIR__ . '/SimpleXLSX.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

/**
 * xlsx_parser.php — Lightweight OpenXML Wrapper powered by SimpleXLSX
 *
 * Provides a clean interface for reading Excel .xlsx files.
 * Uses native SimpleXLSX parser with graceful fallback when ZipArchive is disabled.
 */
class XlsxParser
{
    /** Max allowed file size (10MB) */
    public const MAX_FILE_SIZE = 10485760;

    /**
     * Check if XLSX parsing is supported by the server environment.
     */
    public static function isSupported(): bool
    {
        return class_exists('AnythingInside\Import\SimpleXLSX') && SimpleXLSX::isSupported();
    }

    /**
     * Validate an uploaded XLSX file.
     *
     * @param  array $fileInfo Element from $_FILES
     * @param  int   $maxBytes Max file size limit
     * @throws Exception if validation fails or server extensions are missing
     */
    public static function validateUpload(array $fileInfo, int $maxBytes = self::MAX_FILE_SIZE): void
    {
        if (!self::isSupported()) {
            throw new Exception(
                'Excel (.xlsx) parsing is unavailable on your server because the required PHP server extension (ZipArchive) is disabled. ' .
                'Please save your file as a CSV (.csv) file and upload it instead.'
            );
        }

        $error = $fileInfo['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error !== UPLOAD_ERR_OK) {
            $msgs = [
                UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload size limit.',
                UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload size limit.',
                UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
                UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
            ];
            throw new Exception($msgs[$error] ?? 'File upload error code: ' . $error);
        }

        $tmpFile = $fileInfo['tmp_name'] ?? '';
        if (empty($tmpFile) || !file_exists($tmpFile)) {
            throw new Exception('Uploaded file could not be found on the server.');
        }

        $fileSize = (int) filesize($tmpFile);
        if ($fileSize === 0) {
            throw new Exception('The uploaded file is empty (0 bytes).');
        }
        if ($fileSize > $maxBytes) {
            $mb    = round($fileSize / 1048576, 2);
            $maxMb = round($maxBytes  / 1048576, 1);
            throw new Exception("File size ({$mb} MB) exceeds the {$maxMb} MB limit.");
        }

        $ext = strtolower(pathinfo($fileInfo['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext !== 'xlsx') {
            throw new Exception("Expected an Excel file (.xlsx) but received .{$ext}.");
        }
    }

    /**
     * Parse single-sheet or default first sheet.
     */
    public static function parse(string $filePath): array
    {
        return self::parseBestSheet($filePath);
    }

    /**
     * Parse the best worksheet from an .xlsx file using SimpleXLSX.
     *
     * @param  string $filePath Absolute path to the .xlsx file
     * @param  array  $aliasMap Field alias dictionary for header evaluation
     * @param  array  $keywords Keywords to match against sheet name (e.g. ['materials', 'stock'])
     * @return array<int, array<int, string>> 2D string matrix
     * @throws Exception if parsing fails or server extensions are missing
     */
    public static function parseBestSheet(string $filePath, array $aliasMap = [], array $keywords = []): array
    {
        if (!self::isSupported()) {
            throw new Exception(
                'Excel (.xlsx) parsing is unavailable on your server because the required PHP server extension (ZipArchive) is disabled. ' .
                'Please save your file as a CSV (.csv) file and upload it instead.'
            );
        }

        $xlsx = SimpleXLSX::parse($filePath);
        if ($xlsx === null) {
            throw new Exception('Could not parse the XLSX file. It may be corrupted or unreadable.');
        }

        $sheets = $xlsx->sheets();
        if (empty($sheets)) {
            throw new Exception('No worksheet data found inside the XLSX file.');
        }

        if (count($sheets) === 1) {
            return $sheets[0]['rows'] ?? [];
        }

        // Multi-sheet scoring algorithm
        $bestSheetIndex = 0;
        $bestScore      = -1;

        foreach ($sheets as $index => $sheet) {
            $rows = $sheet['rows'] ?? [];
            if (empty($rows)) {
                continue;
            }

            $headerRow = $rows[0] ?? [];
            $score     = 0;

            // 1. Alias matching score
            if (!empty($aliasMap) && class_exists('AnythingInside\Import\CsvParser')) {
                $mapped = CsvParser::mapColumns($headerRow, $aliasMap);
                $aliasCount = count($mapped['map'] ?? []);
                $score += ($aliasCount * 10);
            }

            // 2. Keyword score
            if (!empty($keywords)) {
                $normalizedSheetName = strtolower(trim($sheet['name'] ?? ''));
                foreach ($keywords as $kw) {
                    if (str_contains($normalizedSheetName, strtolower($kw))) {
                        $score += 15;
                    }
                }
            }

            // 3. Row count weight
            if (count($rows) > 1) {
                $score += 1;
            }

            if ($score > $bestScore) {
                $bestScore      = $score;
                $bestSheetIndex = $index;
            }
        }

        return $sheets[$bestSheetIndex]['rows'] ?? [];
    }
}