<?php
namespace Inspection\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Http\Request;
use Laminas\Http\Client\Adapter\Curl;
use Laminas\Http\Client;

class ValidAddress extends AbstractFilter
{
    public function filter($value)
    {
        $dataFiltered = '';
        
        $uri = 'https://geocoding.geo.census.gov/geocoder/locations/onelineaddress?address=' . urlencode($value) . '&benchmark=4&format=json';
        
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($uri);
        $request->setContent($request->getPost()->toString());
        $dataFiltered = $request->toString();
        
        $adapter = new Curl();
        $client = new Client();
        
        $client->setAdapter($adapter);
        $client->setRequest($request);
        
        $response = $client->send();
        
        $json = json_decode($response->getContent());
        
        if (sizeof($json->result->addressMatches) == 1) {
            $matchedAddress = explode(',', $json->result->addressMatches[0]->matchedAddress);
            $dataFiltered = $matchedAddress[0];
            return $dataFiltered;
        }
        
        return $value;
    }
}