<?php
namespace AnythingInside\Import;

use Exception;

/**
 * import_manager.php — Unified Enterprise Import Engine Facade
 *
 * Serves as the single orchestration facade for file uploads (.csv and .xlsx).
 *
 * Responsibilities:
 *  1. Upload validation (file size, upload status codes, file availability)
 *  2. Extension & MIME detection (.csv, .xlsx)
 *  3. Parser selection & multi-sheet resolution (CsvParser vs XlsxParser)
 *  4. Row matrix normalization (BOM removal, whitespace trimming, control char stripping, filtering empty rows)
 *  5. Unified Exception throwing with actionable user & technical diagnostic details
 *
 * Returns normalized 2D string matrix: array<int, array<int, string>>
 */

// Load underlying parsers if not already loaded
$baseDir = __DIR__;
if (!class_exists('AnythingInside\Import\CsvParser')) {
    $csvFile = $baseDir . '/csv_parser.php';
    if (file_exists($csvFile)) {
        require_once $csvFile;
    }
}
if (!class_exists('AnythingInside\Import\XlsxParser')) {
    $xlsxFile = $baseDir . '/xlsx_parser.php';
    if (file_exists($xlsxFile)) {
        require_once $xlsxFile;
    }
}

class ImportException extends Exception
{
    public string $errorType;
    public string $technicalMessage;

    public function __construct(string $userMessage, string $errorType = 'general', string $technicalMessage = '')
    {
        parent::__construct($userMessage);
        $this->errorType        = $errorType;
        $this->technicalMessage = $technicalMessage ?: $userMessage;
    }
}

class ImportManager
{
    /** Max allowed file size in bytes (10MB) */
    public const MAX_FILE_SIZE = 10485760;

    /** Allowed extensions */
    public const ALLOWED_EXTENSIONS = ['csv', 'xlsx'];

    /**
     * Main entry point: process an uploaded file array from $_FILES.
     *
     * @param  array  $fileInfo  Element from $_FILES (e.g. $_FILES['file'] or $_FILES['csv_file'])
     * @param  int    $maxBytes  Maximum file size limit
     * @param  array  $aliasMap  Optional field alias map for smart XLSX sheet selection
     * @param  array  $keywords  Optional sheet name keywords (e.g. ['material', 'stock'])
     * @return array<int, array<int, string>> Normalized 2D string array
     * @throws ImportException
     */
    public static function parseUploadedFile(
        array $fileInfo,
        int $maxBytes = self::MAX_FILE_SIZE,
        array $aliasMap = [],
        array $keywords = []
    ): array {
        // 1. Validate file upload payload
        self::validateUploadPayload($fileInfo, $maxBytes);

        $tmpFile      = $fileInfo['tmp_name'];
        $originalName = $fileInfo['name'] ?? 'file';
        $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // 2. Parse file according to format
        try {
            if ($extension === 'xlsx') {
                if (!class_exists('AnythingInside\Import\XlsxParser')) {
                    throw new ImportException(
                        'Server Error: The XLSX parser component is missing.',
                        'server_error',
                        'XlsxParser class not found'
                    );
                }

                if (!XlsxParser::isSupported()) {
                    throw new ImportException(
                        'Excel (.xlsx) parsing is unavailable on your server because the required PHP server extension (ZipArchive) is disabled. ' .
                        'Please save your file as a CSV (.csv) file and upload it instead.',
                        'extension_disabled',
                        'PHP ZipArchive extension is disabled on this server node'
                    );
                }

                XlsxParser::validateUpload($fileInfo, $maxBytes);
                $rawRows = XlsxParser::parseBestSheet($tmpFile, $aliasMap, $keywords);
            } else {
                if (!class_exists('AnythingInside\Import\CsvParser')) {
                    throw new ImportException(
                        'Server Error: The CSV parser component is missing.',
                        'server_error',
                        'CsvParser class not found'
                    );
                }
                $rawRows = CsvParser::parseCsv($tmpFile);
            }
        } catch (ImportException $ie) {
            throw $ie;
        } catch (Exception $e) {
            throw new ImportException(
                "Could not read the uploaded {$extension} file. " . $e->getMessage(),
                'file_read',
                $e->getMessage()
            );
        }

        // 3. Normalize rows (clean control characters, trim whitespace, strip empty rows)
        $cleanRows = self::normalizeRows($rawRows);

        if (count($cleanRows) < 2) {
            throw new ImportException(
                'The uploaded file is empty or contains no data rows.',
                'empty_file',
                'Header or data rows missing after cleaning (total rows: ' . count($cleanRows) . ')'
            );
        }

        return $cleanRows;
    }

    /**
     * Validate $_FILES structure, upload status code, file size, and extension.
     *
     * @throws ImportException
     */
    private static function validateUploadPayload(array $fileInfo, int $maxBytes): void
    {
        $errorCode = $fileInfo['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($errorCode !== UPLOAD_ERR_OK) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize limit on the server.',
                UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE specified in the form.',
                UPLOAD_ERR_PARTIAL    => 'The file was only partially uploaded. Please try again.',
                UPLOAD_ERR_NO_FILE    => 'No file was uploaded. Please select a file.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: missing temporary folder for file uploads.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: failed to write file to disk.',
                UPLOAD_ERR_EXTENSION  => 'A server extension blocked the file upload.',
            ];

            $msg = $uploadErrors[$errorCode] ?? ('Upload error code: ' . $errorCode);
            throw new ImportException($msg, 'upload_error', 'PHP UPLOAD_ERR code: ' . $errorCode);
        }

        $tmpFile = $fileInfo['tmp_name'] ?? '';
        if (empty($tmpFile) || !file_exists($tmpFile)) {
            throw new ImportException(
                'Uploaded temporary file was not found on the server.',
                'upload_error',
                'tmp_name empty or file_exists failed'
            );
        }

        if (!is_readable($tmpFile)) {
            throw new ImportException(
                'Uploaded temporary file is not readable by the server.',
                'permission_error',
                'is_readable failed on ' . $tmpFile
            );
        }

        $fileSize = filesize($tmpFile);
        if ($fileSize === false || $fileSize === 0) {
            throw new ImportException(
                'The uploaded file is empty (0 bytes).',
                'empty_file',
                'filesize returned 0 or false'
            );
        }

        if ($fileSize > $maxBytes) {
            $mb = round($fileSize / 1048576, 2);
            $maxMb = round($maxBytes / 1048576, 1);
            throw new ImportException(
                "File size ({$mb} MB) exceeds the maximum allowed limit of {$maxMb} MB.",
                'file_size',
                "File size {$fileSize} > maxBytes {$maxBytes}"
            );
        }

        $originalName = $fileInfo['name'] ?? 'file';
        $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new ImportException(
                "Unsupported file format (." . strtoupper($extension) . "). Please upload a CSV (.csv) or Excel (.xlsx) file.",
                'invalid_type',
                "Extension '{$extension}' not in allowed list: " . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }
    }

    /**
     * Clean and normalize a 2D array of raw rows.
     *  - Strips BOM markers from string values
     *  - Removes invisible control characters (\x00-\x1F, \x7F)
     *  - Trims leading/trailing whitespace
     *  - Filters out completely blank rows
     *
     * @param  array<int, array<int, string>> $rows
     * @return array<int, array<int, string>>
     */
    public static function normalizeRows(array $rows): array
    {
        $normalized = [];

        foreach ($rows as $rIndex => $row) {
            if (!is_array($row)) {
                continue;
            }

            $cleanRow = [];
            $hasValue = false;

            foreach ($row as $cIndex => $cell) {
                $val = (string) ($cell ?? '');

                // Strip UTF-8 BOM if present on first cell
                if ($rIndex === 0 && $cIndex === 0) {
                    $val = preg_replace('/^\xEF\xBB\xBF/', '', $val);
                }

                // Clean invisible non-printable control characters
                $val = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $val);
                $val = trim($val);

                if ($val !== '') {
                    $hasValue = true;
                }

                $cleanRow[] = $val;
            }

            // Only keep non-empty rows
            if ($hasValue) {
                $normalized[] = $cleanRow;
            }
        }

        return $normalized;
    }
}