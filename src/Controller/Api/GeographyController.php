<?php

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Service\Geojson\Region\AllRegionsBasicDataComposer;
use App\Service\Statistic\FindData;
use Symfony\Component\Routing\Annotation\Route;

class GeographyController extends BaseApiController
{

	#[Route('/composer')]
	public function composer(AllRegionsBasicDataComposer $composer)
	{
		$data = $composer->getData();
		dd($data);
	}

	#[Route('/test')]
	public function test()
	{
		$countries = [
			'cz' => 'Česká republika'
		];

		$find = new FindData();
		$_regions = $find->getRegionsData();

		$regions = [];
		foreach ($_regions as $region) {
			$props = $region['properties'];
			$regions[$props['ISO3166-2']] = [
				'name' => $props['name'],
				'latitude' => $props['city']['coords'][1],
				'longitude' => $props['city']['coords'][0],
			];
		}

		return $this->json($regions);
		$regions = [
			'cz' => [
				''
			]
		];
	}

	#[Route('/districts')]
	public function districts()
	{
		$find = new FindData();


		$regions = array_keys(json_decode($this->getJsonData(), true));

		$districts = [];

		foreach ($regions as $region) {
			$_districts = $find->getRegionDetails($region);

			if(!array_key_exists($region, $districts)){
				$districts[$region] = [];
			}

			foreach ($_districts as $district) {
				$props = $district['properties'];
				$districts[$region][$props['district_id']] = [
					'name' => $props['name'],
					'latitude' => 0,
					'longitude' => 0
				];
			}
		}


		return $this->json($districts);
	}

	#[Route('/test2')]
	public function test2()
	{
		$find = new FindData();
		$_regions = $find->getRegionsData();
		$regions = [];
		$region = $_regions[0];

		foreach ($_regions as $region) {
			$props = $region['properties'];
			$id = $props['ISO3166-2'];
			$regions[$props['ISO3166-2']] = $region['geometry'];
			$_districts = $find->getRegionDetails($id);

			foreach ($_districts as $district) {
				$props = $district['properties'];
				$folder = __DIR__."/../../../storage/geography/districts/{$id}";

				$f = fopen($folder."/{$props['district_id']}.json", "w") or die("Unable to open file!");
				fwrite($f, json_encode($district['geometry']));
				fclose($f);
			}


		}
		return $this->json($regions);

//		$regions = array_keys(json_decode($this->getJsonData(), true));
//
//		$districts = [];
//
//		foreach ($regions as $region) {
//			$_districts = $find->getRegionDetails($region);
//
//			foreach ($_districts as $district) {
//				$props = $district['properties'];
//				$districts[$props['ISO3166-2']] = $props['trashes'];
//			}
//		}

//		return $this->json($districts);
	}

	private function getJsonData(): string
	{
		return '{"CZ-10":{"name":"Hlavn\u00ed m\u011bsto Praha","latitude":50.0596654,"longitude":14.4656111},"CZ-64":{"name":"Jihomoravsk\u00fd kraj","latitude":49.1248979,"longitude":16.5946806},"CZ-41":{"name":"Karlovarsk\u00fd kraj","latitude":50.1753496,"longitude":12.6961646},"CZ-31":{"name":"Jiho\u010desk\u00fd kraj","latitude":49.0864602,"longitude":14.5700852},"CZ-20":{"name":"St\u0159edo\u010desk\u00fd kraj","latitude":50.0601476,"longitude":14.4659588},"CZ-72":{"name":"Zl\u00ednsk\u00fd kraj","latitude":49.19695,"longitude":17.7626317},"CZ-42":{"name":"\u00dasteck\u00fd kraj","latitude":50.5663256,"longitude":13.7966001},"CZ-63":{"name":"Kraj Vyso\u010dina","latitude":49.3994046,"longitude":15.6532419},"CZ-51":{"name":"Libereck\u00fd kraj","latitude":50.7475604,"longitude":14.9876249},"CZ-71":{"name":"Olomouck\u00fd kraj","latitude":49.8591215,"longitude":17.3150497},"CZ-53":{"name":"Pardubick\u00fd kraj","latitude":49.8898842,"longitude":16.1151024},"CZ-80":{"name":"Moravskoslezsk\u00fd kraj","latitude":49.8602829,"longitude":18.0028909},"CZ-52":{"name":"Kr\u00e1lov\u00e9hradeck\u00fd kraj","latitude":50.4094091,"longitude":15.844874},"CZ-32":{"name":"Plze\u0148sk\u00fd kraj","latitude":49.522842,"longitude":13.1204268}}';
	}

}