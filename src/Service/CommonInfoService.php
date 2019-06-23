<?php


namespace App\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class CommonInfoService
{
    private $filePath;
    private $data;

    /**
     * CommonInfoService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->filePath = $container->getParameter('kernel.project_dir').'/config/common_info.yaml';
        $this->data = new ArrayCollection(Yaml::parseFile($this->filePath));
    }

    public function setParameter($key, $value): self
    {
        $this->data->set($key, $value);
        return $this;
    }

    public function getParameter($key)
    {
        return $this->data->get($key);
    }

    public function getData(): ArrayCollection
    {
        return $this->data;
    }

    public function flush()
    {
        file_put_contents($this->filePath, Yaml::dump($this->data->toArray()));
    }
}