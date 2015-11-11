<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: bots.php,v 1.8 2006/01/11 06:55:58 mclap Exp $
#
# Bot identificator module
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

header("Vary: User-Agent");

if(!empty($HTTP_USER_AGENT) && !defined("IS_ROBOT") && empty($is_robot)) {
	$ua = array(
		"X-Cart info" => array("X-Cart info","curl/7.21.0","PycURL/7.19.5"),
		"Other" => array ("NerdyBot","ShortLinkTranslate","YahooCacheSystem","ContextAd","WillyBot/1.1","WorldBrewBot/2.1","robots",
		                  "WebIndex","Wget/","Twitterbot/1.0","YisouSpider","betaBot","psbot","Xenu","SEOstats","Wotbox/2.01","CCBot/2.0","Spider/Bot","PHP/5.4.32",
						  "tbot","ltx71","TurnitinBot"),
		"FaceBook" => array("facebookexternalhit/1.1"),
		"Pinterest" => array ("Pinterest/0.2","Pinterest/0.1","Pinterest/4.1.1"),
		"Ahrefs" => array("AhrefsBot/5.0","adidxbot/1.1"),
        "Spotbot" => array("spotbot - spotbot@indix.com","DotBot/1.1","Mail.RU_Bot/2.0","rogerbot/1.1","spotbot"),
		"Google" => array("Googlebot","Mediapartners-Google","AdsBot-Google-Mobile","AdsBot-Google","TwengaBot-2.0","Googlebot-Image/1.0","GoogleBot/2.1","TwengaBot","AdsBot"),
		"Bing" => array("bingbot"),
		"TheFind" => array("FatBot","ShopWiki/1.0","com.thefind.Shopping/3.2.1"),
		"Excita" => array("ArchitextSpider"), 
		"Altavista" => array("Scooter","vscooter","Mercator","AltaVista-Intranet"), 
		"Lycos" => array("Lycos_Spider"), 
		"Northern Light" => array("Gulliver"), 
		"AllTheWeb" => array("FAST-WebCrawler","fastlwspider","Wget"), 
		"Inktomi" => array("Slurp","Yahoo!"),
		"Teoma" => array("teoma_agent1"),
		"Yandex" => array("Yandex","YandexImages/3.0","YandexBot/3.0","YandexBot"),
		"Amazon" => array("aranhabot","Amazon CloudFront","Amazon"),
		"Yahoo" => array("YahooSeeker"),
		"Abacho" => array("AbachoBOT"),
		"Abcdatos" => array("abcdatos_botlink"),
		"Aesop" => array("AESOP_com_SpiderMan"),
		"Ah-ha" => array("ah-ha.com crawler"),
		"Alexa" => array("ia_archiver"),
		"Acoon" => array("Acoon Robot"),
		"Antisearch" => array("antibot"),
		"Atomz" => array("Atomz"),
		"Buscaplus" => array("Buscaplus Robi"),
                "ShopperTom" => array("ShopperTom","MJ12Bot","Pimonster","ShopperTom/"),
		"Baidu" => array ("Baiduspider","Baiduspider-image","Sogou","Baiduspider-image+","Baiduspider/2.0","Baiduspider-image+(+http://www.baidu.com/search/spider.htm)"),
		"CanSeek" => array("CanSeek"),
		"ChristCrawler" => array("ChristCRAWLER"),
		"Crawler" => array("Crawler"),
		"Daadle" => array("DaAdLe.com ROBOT"),
		"Daum" => array("RaBot"),
		"DeepIndex" => array("DeepIndex"),
		"Ditto" => array("DittoSpyder"),
		"Domanova" => array("Jack"),
		"Entire web" => array("Speedy Spider"),
		"Euroseek" => array("Arachnoidea"),
		"EZResults" => array("EZResult"),
		"Fast search" => array("Fast PartnerSite Crawler","FAST Data Search Crawler"),
		"Fireball" => array("KIT-Fireball"),
		"Fyber search" => array("FyberSearch"),
		"Galaxy" => array("GalaxyBot"),
		"Geckobot" => array("geckobot"),
		"GenDoor" => array("GenCrawler"),
		"Geona" => array("GeonaBot"),
		"Goo" => array("moget/2.0"),
		"Girafa" => array("Aranha"),
		"Hoppa" => array("Toutatis"),
		"Hubat" => array("Hubater"),
		"IlTrovatore" => array("IlTrovatore-Setaccio"),
		"IncyWincy" => array("IncyWincy"),
		"InTags" => array("Mole2"),
		"MP3Bot" => array("MP3Bot"),
		"IP3000" => array("ip3000.com-crawler"),
		"Kuloko" => array("kuloko-bot"),
		"Lexis-Nexis" => array("LNSpiderguy"),
		"Look" => array("NetResearchServer"),
		"Look smart" => array("MantraAgent"),
		"Loop improvements" => array("NetResearchServer"),
		"Joocer" => array("JoocerBot"),
		"Mirage" => array("HenryTheMiragoRobot"),
		"mozDex" => array("mozDex"),
		"MSN" => array("MSNBOT","msnbot-media","msnbot"),
		"Northern light" => array("Gulliver"),
		"Objects Search" => array("ObjectsSearch"),
		"Pico Search" => array("PicoSearch"),
		"Portal Juice" => array("PJspider"),
		"Maxbot" => array("Spider/maxbot.com"),
		"National directory" => array("NationalDirectory-SuperSpider"),
		"Naver" => array("NaverRobot"),
		"OpenFind" => array("Openfind piranha","Openbot"),
		"Pic search" => array("psbot"),
		"PinPoint" => array("CrawlerBoy Pinpoint.com"),
		"Search hippo" => array("Fluffy the spider"),
		"Scrub the Web" => array("Scrubby"),
		"SingingFish" => array("asterias"),
		"SpeedFind" => array("speedfind ramBot xtreme"),
		"Kototoi" => array("Kototoi"),
		"SearchSpider" => array("Searchspider"),
		"SightQuest" => array("SightQuestBot"),
		"SpiderMonkey" => array("Spider_Monkey"),
		"Surf-no-more" => array("Surfnomore Spider"),
		"Teradex Mapper" => array("Teradex_Mapper"),
		"Travel finder" => array("ESISmartSpider"),
		"TraficDublu" => array("Spider TraficDublu"),
		"Tutorgig" => array("Tutorial Crawler"),
		"UK Searcher" => array("UK Searcher Spider"),
		"Vivante" => array("Vivante Link Checker"),
		"Walhello" => array("appie"),
		"Websmostlinked.com" => array("Nazilla"),
		"WebTop" => array("MuscatFerret"),
		"WiseNut" => array("ZyBorg"),
		"World search center" => array("WSCbot"),
		"Yellow pet" => array("Yellopet-Spider"),
		"W3C validator" => array("W3C_Validator"),
		"Tooter" => array("Tooter"),
		"Alligator" => array("Alligator"),
		"BatchFTP" => array("BatchFTP"),
		"ChinaClaw" => array("ChinaClaw"),
		"Download accelerator" => array("DA"),
		"NetZIP" => array("Download Demon","NetZip Downloader","SmartDownload"),
		"Download Master" => array("Download Master"),
		"Download Ninja" => array("Download Ninja"),
		"Download Wonder" => array("Download Wonder"),
		"Ez Auto Downloader" => array("Ez Auto Downloader"),
		"FreshDownload" => array("FreshDownload"),
		"Go!Zilla" => array("Go!Zilla"),
		"GetRight" => array("GetRight"),
		"GetSmart" => array("GetSmart"),
		"HiDownload" => array("HiDownload"),
		"FlagGet" => array("JetCar","FlashGet"),
		"Kapere" => array("Kapere"),
		"Kontiki" => array("Kontiki Client"),
		"LeechFTP" => array("LeechFTP"),
		"LeechGet" => array("LeechGet"),
		"LightningDownload" => array("LightningDownload"),
		"Mass Downloader" => array("Mass Downloader"),
		"MetaProducts" => array("MetaProducts"),
		"NetAnts" => array("NetAnts"),
		"NetButler" => array("NetButler"),
		"NetPumper" => array("NetPumper"),
		"Net Vampire" => array("Net Vampire"),
		"Nitro Downloader" => array("Nitro Downloader"), 
		"Octopus" => array("Octopus"), 
		"PuxaRapido" => array("PuxaRapido"),
		"RealDownload" => array("RealDownload"), 
		"SpeedDownload" => array("SpeedDownload"), 
		"WebDownloader" => array("WebDownloader"),
		"WebLeacher" => array("WebLeacher"), 
		"WebPictures" => array("WebPictures"), 
		"X-Uploader" => array("X-Uploader"),
		"DigOut4U" => array("DigOut4U"), 
		"DISCoFinder" => array("DISCoFinder"), 
		"eCatch" => array("eCatch"),
		"EirGrabber" => array("EirGrabber"), 
		"ExtractorPro" => array("ExtractorPro"), 
		"FairAd" => array("FairAd Client"),
		"iSiloWeb" => array("iSiloWeb"), 
		"Kenjin" => array("Kenjin Spider"), 
		"MS IE 4.0" => array("MSIECrawler","MSProxy"),
		"NexTools" => array("NexTools"), 
		"Offline Explorer" => array("Offline Explorer"), 
		"NetAttache" => array("NetAttache"),
		"PageDown" => array("PageDown"), 
		"ParaSite" => array("ParaSite"), 
		"Searchworks" => array("Searchworks Spider"),
		"SiteMapper" => array("SiteMapper"), 
		"SiteSnagger" => array("SiteSnagger"), 
		"SuperBot" => array("SuperBot"),
		"Teleport Pro" => array("Teleport Pro"), 
		"Web2Map" => array("Web2Map"), 
		"WebAuto" => array("WebAuto"),
		"WebCopier" => array("WebCopier"), 
		"Webdup" => array("Webdup"), 
		"WebFetch" => array("WebFetch"),
		"WebReaper" => array("WebReaper"),
		"Webrobot" => array("Webrobot"),
		"Website eXtractor" => array("Website eXtractor"),
		"WebSnatcher" => array("WebSnatcher"),
		"WebStripper" => array("WebStripper"),
		"WebTwin" => array("WebTwin"),
		"WebVCR" => array("WebVCR"),
		"WebZIP" => array("WebZIP"),
		"World Wide Web Offline Explorer" => array("WWWOFFLE"),
		"Xaldon" => array("Xaldon WebSpider")
		);

	$hosts = array(
		"Infoseek" => array('198.5.210.','204.162.96.','204.162.97.','204.162.98.','205.226.201.','205.226.203.','205.226.204.'),
		"Lycos" => array('206.79.171.','207.77.90.','208.146.26.','209.67.228.','209.67.229.')
	);

	if (!empty($ua[$HTTP_USER_AGENT])) {
		define("IS_ROBOT", 1);
		define("ROBOT", $HTTP_USER_AGENT);
	}
	else {
		foreach ($ua as $k => $v) {
			foreach ($v as $u) {
				if (stristr($HTTP_USER_AGENT, $u) !== false) {
					define("IS_ROBOT", 1);
					define("ROBOT", $k);
					break;
				}
			}

			if (defined("IS_ROBOT")) break;
		}
	}

	if (!defined("IS_ROBOT") && !empty($REMOTE_ADDR)) {
		foreach ($hosts as $k => $v) {
			foreach ($v as $u) {
				if (strncmp($REMOTE_ADDR, $u, strlen($u)) === 0) {
					define("IS_ROBOT", 1);
					define("ROBOT", $k);
					break;
				}
			}

			if (defined("IS_ROBOT")) break;
		}
	}

#
## 19.11.2014.
###
                if (!defined("IS_ROBOT")){
                        foreach ($ua as $k => $v) {
                                foreach ($v as $u) {
                                        if (stristr($u, $HTTP_USER_AGENT) !== false) {
                                                define("IS_ROBOT", 1);
                                                define("ROBOT", $k);
                                                break;
                                        }
                                }

                                if (defined("IS_ROBOT")) break;
                        }
                }
###
##
#


	unset($ua, $hosts);

	if (defined("IS_ROBOT")) {
		$is_robot = 'Y';
		$robot = ROBOT;
	} else {
		$is_robot = 'N';
	}

} elseif (defined("IS_ROBOT")) {
	$is_robot = 'Y';
} elseif (!empty($is_robot)) {
	if ($is_robot == 'Y') {
		define("IS_ROBOT", 1);
		define("ROBOT", $robot);
	}
}
?>
