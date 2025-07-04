<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class ReceiptService
{
  public function __construct(private Database $db) {}

  public function validateFile(?array $file)
  {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
      throw new ValidationException([
        'receipt' => ['Failed to upload file']
      ]);
    }

    $maxFileSizeMB = 3 * 1024 * 1024;

    if ($file['size'] > $maxFileSizeMB) {
      throw new ValidationException([
        'receipt' => ['File upload is too large']
      ]);
    }

    $originalFileName = $file['name'];

    if (!preg_match('/^[A-za-z0-9\s._-]+$/', $originalFileName)) {
      throw new ValidationException([
        'receipt' => ['File upload is too large']
      ]);
    }

    $clientMimeType = $file['type'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];

    if (!in_array($clientMimeType, $allowedMimeTypes)) {
      throw new ValidationException([
        'receipt' => ['Invalid file type']
      ]);
    }
  }

  public function upload(array $file, int $transaction)
  {
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

    $uploadPath = Paths::STORAGE_UPLOADS . "/{$newFilename}";

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
      throw new ValidationException(['receipt' => ['Failed to upload file']]);
    }

    $sql = <<<SQL
    INSERT INTO receipts(transaction_id, original_filename, storage_filename, media_type)
    VALUES(:transaction_id, :original_filename, :storage_filename, :media_type)
    SQL;

    $params = [
      'transaction_id' => (int) $transaction,
      'original_filename' => $file['name'],
      'storage_filename' => $newFilename,
      'media_type' => $file['type']
    ];

    $this->db->query($sql, $params);
  }

  public function getReceipt(string $id)
  {
    $sql = <<<SQL
    SELECT * FROM receipts WHERE id = :id
    SQL;

    $param = ['id' => $id];

    return $this->db->query($sql, $param)->find();
  }

  public function read(array $receipt)
  {
    $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

    if (!file_exists($filePath)) redirectTo('/');

    header("Content-Disposition: inline;filename={$receipt['original_filename']}");
    header("Content-Type: {$receipt['media_type']}");

    readfile($filePath);
  }

  public function delete(array $receipt)
  {
    $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];
    unlink($filePath);

    $sql = <<<SQL
    DELETE FROM receipts WHERE id = :id
    SQL;

    $param = ['id' => $receipt['id']];

    $this->db->query($sql, $param);
  }
}
