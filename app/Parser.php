<?php

namespace Tuzk\App;

class Parser
{
    private $path;
    private $mode;
    private $file;


    public function read($path, $mode = "hex")
    {
        $this->path = $path;
        $this->mode = $mode;
        $file = file_get_contents($path);
        $tmp = [];
        foreach (explode("\n", $file) as $line) {
            if (($line != "") && ($line[0] !== "#")) {
                $line = preg_split('#\s+#', $line, 2);
                if (array_key_exists($line[1], $tmp)) {
                    $tmp[$line[0]] = $tmp[$line[1]];
                } else {
                    $tmp[$line[0]] = $line[1];
                }
            }
        }
        foreach ($tmp as $key => $color) {
            $tmp[$key] = $this->format($color);
        }
        $this->file = $tmp;
        return $this;
    }

    private function format($color)
    {
        if ($this->mode === "hex") {
            return $color;
        }

        if ($this->mode === "rgb" && ((strpos($color, "#") === 0) && (strlen($color) === 7))) {
            return rgbMode($color);
        }
    }

    public function replace($search, $replace, $path)
    {
        $file = file_get_contents($path);
        $file = preg_replace("/%%$search%%/i", $replace, $file);
        file_put_contents($path, $file);

        return $this;
    }

    public function append($path, $data)
    {
        $file = file_get_contents($path);
        $content = $data . $file;
        file_put_contents($path, $content);

        return $this;
    }

    public static function merge($default, $scheme, $mode)
    {
        return array_merge(
            (new self)->setFile($default, $mode)->getFile(),
            (new self)->setFile($scheme, $mode)->getFile()
        );
    }

    public function get()
    {
        return $this->file;
    }

    public function getVar($var)
    {
        foreach ($this->file as $key => $value) {
            if ($key === $var) {
                return $value;
            }
        }
        return false;
    }
}
