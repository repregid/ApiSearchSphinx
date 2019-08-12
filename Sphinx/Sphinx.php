<?php

namespace Repregid\ApiSearchSphinx\Sphinx;

use Repregid\ApiBundle\Service\Search\SearchEngineInterface;

/**
 * Class Sphinx
 * @package Repregid\ApiSearchSphinx\Sphinx
 */
class Sphinx implements SearchEngineInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @param $entityName
     * @return string
     */
    public function buildIndex($entityName)
    {
        return $this->prefix.str_replace('\\', '_', strtolower($entityName));
    }

    public function setPrefix($prefix){
        $this->prefix = $prefix;
    }

    /**
     * @return SphinxClient
     */
    protected function getClient()
    {
        return new SphinxClient();
    }

    /**
     * @param string $term
     * @param string $target
     * @param array $fields
     * @return array Array of Ids
     */
    public function findByTerm(string $term, string $target, array $fields = []) : array
    {
        $target = $this->buildIndex($target);
        $term   = mb_strtolower($term, 'UTF-8');

        $client = $this->getClient();

        $client->SetMatchMode(SPH_MATCH_EXTENDED2);
        $client->SetRankingMode(SPH_RANK_SPH04);

        $matches = $client->query($term, $target);

        if (!isset($matches['matches'])) {
            return [];
        }

        $result = [];

        foreach($matches['matches'] as $match) {
            if (!isset($match['attrs']['idx'], $match['weight'])) {
                continue;
            }
            $result[] = ['idx' => $match['attrs']['idx'], 'weight' => $match['weight']];
        }

        return $result;
    }
}