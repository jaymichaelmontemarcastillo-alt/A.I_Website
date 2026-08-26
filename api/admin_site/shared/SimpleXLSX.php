<?php
namespace AnythingInside\Import;

use Exception;
use ZipArchive;
use SimpleXMLElement;

/**
 * SimpleXLSX.php — Standalone Native OpenXML (XLSX) Reader
 *
 * Lightweight, single-file Excel reader using only standard PHP built-ins:
 *  - ZipArchive
 *  - SimpleXML
 *
 * Zero Composer dependencies. Zero external libraries.
 * Designed for maximum compatibility with shared hosting (including InfinityFree).
 */
class SimpleXLSX
{
    private array $sheets = [];
    private array $sheetNames = [];
    private array $sharedStrings = [];

    /**
     * Check whether required PHP server extensions (ZipArchive and SimpleXML) are enabled.
     */
    public static function isSupported(): bool
    {
        return class_exists('ZipArchive') && function_exists('simplexml_load_string');
    }

    /**
     * Factory constructor: parse an XLSX file or raw file data.
     *
     * @param  string  $filename Path to file
     * @param  bool    $isData   True if $filename is raw binary string data
     * @return SimpleXLSX|null  Returns instance on success, null on failure
     */
    public static function parse(string $filename, bool $isData = false): ?self
    {
        $xlsx = new self();
        return $xlsx->parseFile($filename, $isData) ? $xlsx : null;
    }

    /**
     * Get 2D row array for a given worksheet index.
     *
     * @param  int $sheetIndex 0-based worksheet index
     * @return array<int, array<int, string>>
     */
    public function rows(int $sheetIndex = 0): array
    {
        return $this->sheets[$sheetIndex]['rows'] ?? [];
    }

    /**
     * Alias for rows().
     */
    public function sheetRows(int $sheetIndex = 0): array
    {
        return $this->rows($sheetIndex);
    }

    /**
     * Get names of all worksheets in the workbook.
     *
     * @return array<int, string>
     */
    public function sheetNames(): array
    {
        return $this->sheetNames;
    }

    /**
     * Get all parsed worksheets with name and rows matrix.
     *
     * @return array<int, array{name: string, rows: array<int, array<int, string>>}>
     */
    public function sheets(): array
    {
        return $this->sheets;
    }

    /**
     * Internal file parser implementation.
     */
    private function parseFile(string $filename, bool $isData = false): bool
    {
        if (!self::isSupported()) {
            return false;
        }

        $tempPath = null;
        if ($isData) {
            $tempPath = tempnam(sys_get_temp_dir(), 'xlsx_');
            if ($tempPath === false || file_put_contents($tempPath, $filename) === false) {
                return false;
            }
            $filePath = $tempPath;
        } else {
            $filePath = $filename;
        }

        if (!file_exists($filePath) || !is_readable($filePath)) {
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return false;
        }

        $zip = new ZipArchive();
        $res = $zip->open($filePath);

        if ($res !== true) {
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return false;
        }

        try {
            $this->sharedStrings = $this->readSharedStrings($zip);
            $this->readWorksheets($zip);
        } finally {
            $zip->close();
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }

        return !empty($this->sheets);
    }

    /**
     * Extract shared strings lookup table from xl/sharedStrings.xml.
     */
    private function readSharedStrings(ZipArchive $zip): array
    {
        $strings = [];
        $raw     = $zip->getFromName('xl/sharedStrings.xml');

        if ($raw === false || trim($raw) === '') {
            return $strings;
        }

        $xml = $this->parseXml($raw);
        if ($xml === null) {
            return $strings;
        }

        foreach ($xml->si as $si) {
            $strings[] = $this->extractAllText($si->asXML());
        }

        return $strings;
    }

    /**
     * Read all worksheet XML files inside the archive.
     */
    private function readWorksheets(ZipArchive $zip): void
    {
        $relMap = [];
        $relsRaw = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($relsRaw !== false) {
            $relsXml = $this->parseXml($relsRaw);
            if ($relsXml !== null) {
                foreach ($relsXml->Relationship as $rel) {
                    $rId    = (string) ($rel['Id'] ?? '');
                    $type   = (string) ($rel['Type'] ?? '');
                    $target = (string) ($rel['Target'] ?? '');

                    if ($rId !== '' && str_contains($type, '/worksheet') && $target !== '') {
                        if (!str_starts_with($target, 'xl/')) {
                            $target = 'xl/' . ltrim($target, '/');
                        }
                        $relMap[$rId] = $target;
                    }
                }
            }
        }

        $sheets = [];
        $sheetNames = [];
        $wbRaw = $zip->getFromName('xl/workbook.xml');

        if ($wbRaw !== false) {
            $wbXml = $this->parseXml($wbRaw);
            if ($wbXml !== null) {
                $sheetNodes = $this->findChildren($this->findElement($wbXml, 'sheets') ?? $wbXml, 'sheet');
                foreach ($sheetNodes as $sNode) {
                    $sheetName = (string) ($sNode['name'] ?? '');
                    $rId = (string) (
                        $sNode->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id']
                        ?? $sNode['id']
                        ?? ''
                    );

                    $targetPath = $relMap[$rId] ?? null;
                    if ($targetPath === null) {
                        $idx = count($sheets) + 1;
                        $targetPath = "xl/worksheets/sheet{$idx}.xml";
                    }

                    $sheetRaw = $zip->getFromName($targetPath);
                    if ($sheetRaw !== false) {
                        $sheetXml = $this->parseXml($sheetRaw);
                        if ($sheetXml !== null) {
                            $rows = $this->xmlToRows($sheetXml);
                            $sheets[] = [
                                'name' => $sheetName ?: ('Sheet' . (count($sheets) + 1)),
                                'rows' => $rows,
                            ];
                            $sheetNames[] = $sheetName ?: ('Sheet' . (count($sheetNames) + 1));
                        }
                    }
                }
            }
        }

        // Fallback if workbook.xml is missing or unparseable
        if (empty($sheets)) {
            for ($i = 1; $i <= 50; $i++) {
                $targetPath = "xl/worksheets/sheet{$i}.xml";
                $sheetRaw   = $zip->getFromName($targetPath);
                if ($sheetRaw === false) {
                    break;
                }
                $sheetXml = $this->parseXml($sheetRaw);
                if ($sheetXml !== null) {
                    $rows = $this->xmlToRows($sheetXml);
                    $sheets[] = [
                        'name' => "Sheet{$i}",
                        'rows' => $rows,
                    ];
                    $sheetNames[] = "Sheet{$i}";
                }
            }
        }

        $this->sheets     = $sheets;
        $this->sheetNames = $sheetNames;
    }

    /**
     * Convert worksheet SimpleXMLElement to a 2D string matrix.
     */
    private function xmlToRows(SimpleXMLElement $xml): array
    {
        $sheetData = $this->findElement($xml, 'sheetData');
        if ($sheetData === null) {
            return [];
        }

        $rawRows     = [];
        $maxColIndex = 0;

        foreach ($sheetData->row as $rowEl) {
            $rowData = [];
            $cells   = $this->findChildren($rowEl, 'c');

            if (empty($cells)) {
                $rawRows[] = [];
                continue;
            }

            foreach ($cells as $cell) {
                $ref      = (string) ($cell['r'] ?? '');
                $colIndex = $this->colLetterToIndex($ref);
                $cellType = (string) ($cell['t'] ?? '');
                $value    = $this->cellValue($cell, $cellType);

                $rowData[$colIndex] = $value;

                if ($colIndex > $maxColIndex) {
                    $maxColIndex = $colIndex;
                }
            }

            $rawRows[] = $rowData;
        }

        if (empty($rawRows)) {
            return [];
        }

        $totalCols = $maxColIndex + 1;
        $rows      = [];

        foreach ($rawRows as $rowData) {
            $normalized = [];
            for ($i = 0; $i < $totalCols; $i++) {
                $normalized[] = isset($rowData[$i]) ? trim((string) $rowData[$i]) : '';
            }
            $rows[] = $normalized;
        }

        return $rows;
    }

    /**
     * Resolve cell value according to Excel cell type.
     */
    private function cellValue(SimpleXMLElement $cell, string $cellType): string
    {
        switch ($cellType) {
            case 's':
                $index = (int) (string) ($cell->v ?? '-1');
                return $this->sharedStrings[$index] ?? '';

            case 'inlineStr':
                if (isset($cell->is->t)) {
                    return (string) $cell->is->t;
                }
                return $this->extractAllText($cell->asXML());

            case 'b':
                $v = trim((string) ($cell->v ?? ''));
                return ($v === '1' || strtolower($v) === 'true') ? 'TRUE' : 'FALSE';

            default:
                return trim((string) ($cell->v ?? ''));
        }
    }

    /**
     * Convert Excel column letters (A, Z, AA) to 0-based column index.
     */
    private function colLetterToIndex(string $cellRef): int
    {
        $letters = preg_replace('/[0-9]+$/', '', $cellRef);
        if ($letters === null || $letters === '') {
            return 0;
        }

        $letters = strtoupper($letters);
        $index   = 0;
        $len     = strlen($letters);

        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }

    /**
     * Extract concatenated text from <t> tags via regex.
     */
    private function extractAllText(string $rawXml): string
    {
        preg_match_all(
            '/<[a-zA-Z0-9_:]*t(?:\s[^>]*)?>([^<]*)<\/[a-zA-Z0-9_:]*t>/u',
            $rawXml,
            $matches
        );
        return implode('', $matches[1] ?? []);
    }

    private function findElement(SimpleXMLElement $parent, string $name): ?SimpleXMLElement
    {
        if (isset($parent->$name)) {
            return $parent->$name;
        }

        foreach ($parent->getNamespaces(true) as $uri) {
            $children = $parent->children($uri);
            if (isset($children->$name)) {
                return $children->$name;
            }
        }

        return null;
    }

    private function findChildren(SimpleXMLElement $parent, string $name): array
    {
        $plain = $parent->$name;
        if ($plain !== null && count($plain) > 0) {
            $result = [];
            foreach ($plain as $el) {
                $result[] = $el;
            }
            return $result;
        }

        foreach ($parent->getNamespaces(true) as $uri) {
            $nsChildren = $parent->children($uri)->$name;
            if ($nsChildren !== null && count($nsChildren) > 0) {
                $result = [];
                foreach ($nsChildren as $el) {
                    $result[] = $el;
                }
                return $result;
            }
        }

        return [];
    }

    private function parseXml(string $raw): ?SimpleXMLElement
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string(
            $raw,
            'SimpleXMLElement',
            LIBXML_NOENT | LIBXML_COMPACT | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        return ($xml === false) ? null : $xml;
    }
}