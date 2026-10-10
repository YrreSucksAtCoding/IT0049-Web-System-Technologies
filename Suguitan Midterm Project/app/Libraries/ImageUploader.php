<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * ImageUploader
 *
 * Product images and staff avatars need exactly the same handling:
 * rename the file, move it somewhere public, resize it for display, and
 * clear out the file it replaces. Writing that twice would be two places
 * to fix a bug, so it lives here and both controllers call it.
 */
class ImageUploader
{
    /**
     * The validation rules for an optional image field.
     *
     * Takes the form field name. Returns a rule string.
     *
     * ext_in only checks the filename, which a user controls, so mime_in
     * is included to check what the file actually contains.
     */
    public static function rulesFor(string $field): string
    {
        return 'is_image[' . $field . ']'
            . '|mime_in[' . $field . ',image/jpg,image/jpeg,image/png,image/webp]'
            . '|ext_in[' . $field . ',jpg,jpeg,png,webp]'
            . '|max_size[' . $field . ',2048]';   // 2048 KB = 2 MB
    }

    /**
     * True when the request actually carries a file for this field.
     *
     * Takes an UploadedFile or null. Used so the file rules are applied
     * only when something was chosen — which keeps the upload optional
     * without letting a bad file through unchecked.
     */
    public static function wasSubmitted(?UploadedFile $file): bool
    {
        return $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Store an uploaded image and return the filename to save.
     *
     * $file    the uploaded file, or null
     * $folder  a folder name under public/uploads, e.g. 'products'
     * $old     the filename this one replaces, deleted on success
     * $width   target width of the display copy
     * $height  target height of the display copy
     *
     * Returns the new filename, or null when nothing was uploaded — the
     * caller reads null as "leave the stored value alone".
     *
     * Only the filename is ever returned, never a path. The folder is
     * added back by the view, so moving the upload folder later does not
     * mean rewriting every row in the database.
     */
    public static function store(
        ?UploadedFile $file,
        string $folder,
        ?string $old = null,
        int $width = 400,
        int $height = 400
    ): ?string {
        if (! self::wasSubmitted($file)) {
            return null;
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $folder;

        if (! is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // getRandomName() ignores whatever the user called the file.
        // Trusting the original name is how a disguised script gets in.
        $newName = $file->getRandomName();
        $file->move($path, $newName);

        $saved = $path . DIRECTORY_SEPARATOR . $newName;

        // Prepare a display-ready copy, so listing pages are not loading
        // full-size camera photos. This overwrites the moved file.
        service('image')
            ->withFile($saved)
            ->fit($width, $height, 'center')
            ->save($saved);

        self::remove($folder, $old);

        return $newName;
    }

    /**
     * Delete a stored file, if it is there.
     *
     * Takes the folder name and the filename. Does nothing when the
     * filename is empty or the file is already gone.
     */
    public static function remove(string $folder, ?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }

        $full = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename;

        if (is_file($full)) {
            unlink($full);
        }
    }

    /**
     * Build the URL for a stored image, falling back to a placeholder.
     *
     * Takes the folder name, the stored filename, and the placeholder
     * file in public/img. Returns a URL the view can put in an img tag.
     */
    public static function url(string $folder, ?string $filename, string $placeholder): string
    {
        if ($filename === null || $filename === '') {
            return base_url('img/' . $placeholder);
        }

        return base_url('uploads/' . $folder . '/' . $filename);
    }
}
