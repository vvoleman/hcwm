<?php

namespace App\Tests\Service\Zotero\Factory;

class MockZoteroData
{

	public static function getCollectionData(): array
	{
		$data = '{"key":"FEXTSB7P","version":143,"library":{"type":"user","id":9200014,"name":"vvoleman","links":{"alternate":{"href":"https:\/\/www.zotero.org\/vvoleman","type":"text\/html"}}},"links":{"self":{"href":"https:\/\/api.zotero.org\/users\/9200014\/collections\/FEXTSB7P","type":"application\/json"},"alternate":{"href":"https:\/\/www.zotero.org\/vvoleman\/collections\/FEXTSB7P","type":"text\/html"},"up":{"href":"https:\/\/api.zotero.org\/users\/9200014\/collections\/WVXMVF5U","type":"application\/json"}},"meta":{"numCollections":0,"numItems":1},"data":{"key":"FEXTSB7P","version":143,"name":"cs__Zpracov\u00e1n\u00ed odpad\u016f__en__Waste processing__de__Abfallverarbeitung","parentCollection":"WVXMVF5U","relations":[]}}';
		return json_decode($data, true);
	}

	public static function getItemData(): array
	{
		$data = '{"key":"DFTV3XT2","version":167,"library":{"type":"user","id":9200014,"name":"vvoleman","links":{"alternate":{"href":"https:\/\/www.zotero.org\/vvoleman","type":"text\/html"}}},"links":{"self":{"href":"https:\/\/api.zotero.org\/users\/9200014\/items\/DFTV3XT2","type":"application\/json"},"alternate":{"href":"https:\/\/www.zotero.org\/vvoleman\/items\/DFTV3XT2","type":"text\/html"}},"meta":{"creatorSummary":"Nov\u00e1k","numChildren":0},"data":{"key":"DFTV3XT2","version":167,"itemType":"book","title":"cs__Zpracov\u00e1n\u00ed infek\u010dn\u00edch odpad\u016f__en__Processing of infectious waste__de__Behandlung von infekti\u00f6sen Abf\u00e4llen","creators":[{"creatorType":"author","firstName":"Jan","lastName":"Nov\u00e1k"}],"abstractNote":"T\u00edm vznikla zbra\u0148 stra\u0161n\u00e1 a vskutku vra\u017eedn\u00e1. Po\u0159\u00eddiv to v\u0161e zalil do s\u00e1dry a \u0159ekl, \u017ee jede sem, k t\u00e1tovi, ale nejel; hle\u010fme, je to hlas tat\u00ednk\u016fv, n\u011bkdo ho vra\u017ed\u00ed; i jal se ob\u0161\u00edrn\u011b svl\u00e9kat velkolep\u00e9 jelen\u00ed rukavice. Byl to oncle Rohn. Jdi dom\u016f, Minko, k\u00e1zal neodmluvn\u011b. A vy tu po\u010dk\u00e1te, obr\u00e1til se Prokop studem a bolest\u00ed chytal za hlavu. Po\u010dkejte, mn\u011b praskne hla-va; to bude mela. Prokop nal\u00ed\u010dil strategickou diverzi ke schod\u016fm; \u010dty\u0159i mu\u017ei se za\u010dali p\u0159et\u00e1\u010det v tu minutu a vte\u0159inu se mu.","series":"","seriesNumber":"","volume":"","numberOfVolumes":"","edition":"","place":"","publisher":"","date":"","numPages":"","language":"cs","ISBN":"","shortTitle":"","url":"https:\/\/google.com","accessDate":"","archive":"","archiveLocation":"","libraryCatalog":"","callNumber":"","rights":"","extra":"","tags":[{"tag":"WHO"},{"tag":"infek\u010dn\u00ed"},{"tag":"metodika"}],"collections":["FEXTSB7P"],"relations":[],"dateAdded":"2022-05-31T21:06:52Z","dateModified":"2022-05-31T21:08:48Z"}}';
		return json_decode($data, true);
	}

}