<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb61b79819ae9165507c7f3b1ebfa76b0
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spipu\\Html2Pdf\\' => 15,
            'Sources\\' => 8,
        ),
        'O' => 
        array (
            'Outils\\' => 7,
        ),
        'M' => 
        array (
            'Modeles\\' => 8,
        ),
        'F' => 
        array (
            'FPDF\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spipu\\Html2Pdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/spipu/html2pdf/src',
        ),
        'Sources\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Outils\\' => 
        array (
            0 => __DIR__ . '/../..' . '/resources/Outils',
        ),
        'Modeles\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Modeles',
        ),
        'FPDF\\' => 
        array (
            0 => __DIR__ . '/../..' . '/resources/fpdf185',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Datamatrix' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/datamatrix.php',
        'PDF417' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/qrcode.php',
        'TCPDF' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_images.php',
        'TCPDF_IMPORT' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_import.php',
        'TCPDF_PARSER' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_parser.php',
        'TCPDF_STATIC' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_static.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb61b79819ae9165507c7f3b1ebfa76b0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb61b79819ae9165507c7f3b1ebfa76b0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb61b79819ae9165507c7f3b1ebfa76b0::$classMap;

        }, null, ClassLoader::class);
    }
}
