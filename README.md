# FileDrift
FileDrift is a lightweight and user-friendly PHP-based file upload system that enables users to easily upload various types of files to your server. With configurable settings for maximum file sizes, allowed file types, and optional password protection, FileDrift provides a secure and efficient solution for file sharing and storage.

![FileDrift Screenshot](https://github.com/Axmaw98/FileDrift/assets/90964275/02661ada-8bc9-4c02-a057-76d95b5caf3d)



## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Configuration](#configuration)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Introduction

FileDrift is a simple file upload system written in PHP. It provides a straightforward interface for users to upload files to your server. The system includes features such as file size restrictions, allowed file types, random file naming, and more.

## Features

- User-friendly file upload interface.
- Configurable maximum file size per upload.
- Configurable maximum total combined file size.
- Control over allowed file types for upload.
- Randomized file names to prevent conflicts.
- Optional password protection for the upload form.
- Direct links to the uploaded files for easy sharing.



## Configuration

Edit the `index.php` file to configure the settings according to your preferences:

- `$max_file_size`: Maximum size per file in KB.
- `$max_combined_size`: Maximum size for all files combined in KB.
- `$file_uploads`: Maximum number of file uploads at once.
- `$websitename`: Name of your website.
- `$full_url`: Full browser-accessible URL where files will be accessed.
- `$folder`: Path to store uploaded files on your server.
- `$random_name`: Set to `true` for random file names, `false` to use original names.
- `$allow_types`: Array of allowed file extensions.
- `$password`: Optional password for protecting the upload form.


## Usage

1. Visit the URL where the upload form is hosted.

2. If password protection is enabled, provide the correct password to access the upload form.

3. Choose the file you want to upload using the file input fields.

4. Click the "Upload" button to initiate the upload process.

5. Once the upload is complete, you will receive a message indicating the success or failure of each upload.

6. Direct links to the uploaded files will be provided for easy sharing.

## Contributing

Contributions to this project are welcome! If you encounter issues or have suggestions for improvements, please [create an issue](https://github.com/Axmaw98/FileDrift/issues) or submit a pull request.

## Credit

The initial script was created by [Vahid Majidi] in 2010, designed for an earlier version of PHP. In this project, I have undertaken the task of enhancing and updating the codebase to PHP 8, while also giving the graphical user interface (GUI) a modern and sleek flat design.


## License

This project is licensed under the [GNU General Public License version 3.0 (GPL-3.0)](https://github.com/Axmaw98/FileDrift/blob/main/LICENSE). You are free to use, modify, and distribute the code as long as you adhere to the terms and conditions of the GPL-3.0 license. This license ensures that derivative works are also open source and under the same terms, promoting the free distribution and modification of software.

## Copyright

© 2023  Ahmed Kawa
