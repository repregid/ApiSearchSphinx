<?php

namespace Repregid\ApiSearchEngine\Sphinx;


use Repregid\ApiBundle\Service\Search\SearchEngineInterface;

/**
 * Class Sphinx
 * @package Repregid\ApiSearchEngine\Sphinx
 */
class Sphinx implements SearchEngineInterface
{
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
        $target = str_replace('\\', '_', strtolower($target));
        $term   = mb_strtolower($term, 'UTF-8');
        $term   = str_replace(' ', ' | ', $term);

        $client = $this->getClient();

        $client->SetMatchMode(SPH_MATCH_EXTENDED2);
        $client->SetRankingMode(SPH_RANK_SPH04);

        $matches = $client->query($term, $target);

        if (!isset($matches['matches'])) {
            return [];
        }

        $result = [];

        foreach($matches['matches'] as $match) {
            if (!isset($match['attrs']['idx'])) {
                continue;
            }

            $result[] = $match['attrs']['idx'];
        }

        return $result;
    }
}