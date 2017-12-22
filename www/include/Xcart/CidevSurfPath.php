<?php

namespace Xcart;


class CidevSurfPath extends Data
{
    const SURFPATH_TYPE_PRODUCT = 'P';
    const SURFPATH_TYPE_CATEGORY = 'C';
    const SURFPATH_TYPE_BRAND = 'B';
    const SURFPATH_TYPE_HOME = 'H';
    const SURFPATH_TYPE_SEARCH = 'S';
    const SURFPATH_TYPE_TECHNICAL_SEARCH = 'L';
    const SURFPATH_TYPE_STATIC_PAGE = 'T';
    const SURFPATH_TYPE_REFERAL = 'R';
    const SURFPATH_TYPE_CHECKOUT = 'K';
    const SURFPATH_TYPE_ORDER_PLACE = 'O';
    const SURFPATH_TYPE_ORDER_ADD_TO_CART = 'A';

    private $aSurfPathToCleanUrlType = [
        self::SURFPATH_TYPE_PRODUCT => CleanUrl::CLEANURL_TYPE_PRODUCT,
        self::SURFPATH_TYPE_CATEGORY => CleanUrl::CLEANURL_TYPE_CATEGORY,
        self::SURFPATH_TYPE_BRAND => CleanUrl::CLEANURL_TYPE_BRAND,
        self::SURFPATH_TYPE_STATIC_PAGE => CleanUrl::CLEANURL_TYPE_STATIC_PAGE,
    ];

    public function __construct($aData = null)
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_surf_path';

        parent::__construct($aData);
    }

    public static function getLastSurfPath($aSurfType = [])
    {
        global $XCARTSESSID;
        $oRes = null;
        $queryBuilder = Connection::getInstance()->createQueryBuilder();
        $queryBuilder->select('p.*')
            ->from('xcart_cidev_surf_path', 'p')
            ->innerJoin('p', 'xcart_cidev_surf_meta', 's', 'p.meta_id = s.id')
            ->where("s.sessid = '{$XCARTSESSID}'")
            ->orderBy('position', 'DESC')
            ->setMaxResults(1);
        if (!empty($aSurfType)) {
            $queryBuilder->andWhere("p.resource_type IN ('".implode("','",$aSurfType)."')");
        }
        $aRes = $queryBuilder->execute()->fetch();
        if (!empty($aRes)) {
            $oRes = CidevSurfPath::model()->fill($aRes);
        }
        return $oRes;
    }

    public function getUrl()
    {
        $sUrl = null;
        switch ($this->getField('resource_type')) {
            case self::SURFPATH_TYPE_PRODUCT:
            case self::SURFPATH_TYPE_CATEGORY:
            case self::SURFPATH_TYPE_BRAND:
            case self::SURFPATH_TYPE_STATIC_PAGE:
                $sUrl = CleanUrl::model([
                    'resource_id' => $this->getField('resource_id'),
                    'resource_type' => $this->aSurfPathToCleanUrlType[$this->getField('resource_type')]])->getUrl();
                break;
            case self::SURFPATH_TYPE_SEARCH:
                $sUrl = '/keyword/'.$this->getField('additional_data').'/?mode_search=Y';
                break;
        }
        return $sUrl;
    }
}