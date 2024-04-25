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

    public function setPrefix($prefix)
    {
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
        $origTarget = $target;
        $origTerm = $term;
        $target = $this->buildIndex($target);
        $term   = mb_strtolower($term, 'UTF-8');

        $client = $this->getClient();

        $client->SetMatchMode(SPH_MATCH_EXTENDED2);
        $client->SetRankingMode(SPH_RANK_SPH04);
        
        $extraWeight = $fields['extraWeight'] ?? 0;
            
        $client->SetSelect("*, (weight()+ $extraWeight) AS customweight");
        $client->SetLimits(0, 500);
        $client->SetSortMode(SPH_SORT_ATTR_DESC, 'customweight');
        $matches = $client->query($term, $target);

        if (!isset($matches['matches'])) {
            if (($fields['extraWeight'] ?? null) === null) {
                return [];
            } else {
                return $this->findByTerm($origTerm, $origTarget);
            }
        }

        $result = [];

        foreach($matches['matches'] as $match) {
            if (!isset($match['attrs']['idx'], $match['weight'])) {
                continue;
            }
            $result[] = ['idx' => $match['attrs']['idx'], 'weight' => $match['attrs']['customweight'], 'attrs' => $match['attrs']];
        }

        return $result;
    }
}