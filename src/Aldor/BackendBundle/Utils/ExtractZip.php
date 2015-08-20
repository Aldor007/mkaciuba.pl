<?php

namespace Aldor\BackendBundle\Utils;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ExtractZip {

    protected $fs;
    protected $zipNam;
    protected $extracDir;
    protected $zipPath;
    protected $files;

    public function __construct($filePath = 0) {

        $this->fs = new Filesystem();
        if($filePath) {
            $this->zipPath = $filePath;
        } else {
            $this->zipName = 'rand'.rand(1, 99999).'.zip';
            $this->zipPath = '/tmp/'.$this->zipName;
        }
        $this->extracDir = '/tmp/extract'.rand(1, 88888);


    }


    public function extract($zipForm = 0) {

        if ($zipForm) {
            $zipForm->getData()->move('/tmp/', $this->zipName);
        }
        $zip = new \ZipArchive();

        $this->fs->mkdir($this->extracDir);
        if ($zip->open($this->zipPath)) {
            $zip->extractTo($this->extracDir);
            $zip->close();
        } else {
            throw \Exception('Can\'t open zip file!');

        }
       $this->files = Finder::create()
            ->name('*.*')
            ->in($this->extracDir);

        return $this->files;
    }

    public function getZipPath() {
        return $this->zipPath;
    }

    public function clean() {
        $this->fs->remove($this->files);
        $this->fs->remove($this->extracDir);
        $this->fs->remove($this->zipPath);
    }


}
