<?php
namespace De\Idrinth\EntityGenerator;

class EntityNameHandler
{
    /**
     *
     * @var array
     */
    protected $filterCache = array();
    /**
     *
     * @param string $string
     * @return string
     */
    public function toUpperCamelCase($string)
    {
        if (!isset($this->filterCache['uc'][$string])) {
            $parts = explode(
                '_',
                str_replace(array('/', '\\', '-', '.'), '_', $string)
            );
            $formatted = array();
            foreach ($parts as $part) {
                if ($part) {
                    $formatted[] = strtoupper($part{0}).strtolower(substr($part, 1));
                }
            }
            $this->filterCache['uc'][$string] = implode('', $formatted);
        }
        return $this->filterCache['uc'][$string];
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public function toLowerCamelCase($string)
    {
        if (!isset($this->filterCache['lc'][$string])) {
            $uCC = $this->toUpperCamelCase($string);
            $uCC{0} = strtolower($uCC{0});
            $this->filterCache['lc'][$string] = $uCC;
        }
        return $this->filterCache['lc'][$string];
    }
}