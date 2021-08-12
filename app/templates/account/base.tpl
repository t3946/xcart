{extends "account-base.tpl"}

{block 'content'}
    <div class="account account-page"></div>
    <style>
        .shadow-panel {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.2);
        }

        .departments-menu {
            width: 100%;
        }

        .category-menu-item {
            font-weight: 700;
            /*font-size: rem-calc(14);*/
            font-size:  14px;
        }
        .category-menu-link__top-level {
            display: block;
            /*line-height: rem-calc(32);*/
            line-height: 32px;
            /*padding: 0 rem-calc(10);*/
            padding: 0 10px;
            white-space: nowrap;
            overflow: hidden;
        }

        .category-menu-link{
            text-decoration: none;
            transition: all 0.075s ease-out;
            color: #000000;
        }

        .category-menu-link__selected {
            text-decoration: underline;
            background-color: #dfe5e8;
        }


        .category-menu-link-level-2-header {
            font-size: 14px;
        }

        .category-detailed {
            column-count: 3;
            column-gap: 80px;
        }

        .category-menu-link__level-2 {
            display: block;
        }

        .category-menu-link__level-2:hover {
            background: none;
            color: #055a93;
        }

        .category-menu-link__level-3 {
            font-size: 11px;
            height: 16px;
            display: block;
            width: 100%;
        }
        .category-menu-link__level-3:hover {
            background: none;
            color: #055a93;
        }

        .category-menu-link-level-2-header {
            padding-bottom: 2px;
            color: inherit;
        }

        .category-menu-link-level-2-header__underlined {
            border-bottom: 1px solid #c2c2c2;
        }

        .category-menu-group-list {
            break-inside: avoid;
        }
        .group-links-column {
            break-inside: avoid;
        }

        .category-menu-group-item {
            height: 16px;
            display: flex;
        }

        .category-view-all{
            background-color: #e9e9e9;
            color: #055a93;
            max-width: 100%;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            line-height: 22px;
            height: 22px;
            text-align: center;
        }

        .category-view-all-link {
            font-size: 14px;
            color: #055a93;
        }

        .category-view-all-link:hover {
            text-decoration: underline;
            color: #055a93;
        }

        .category-view-all-link:before {
             content: '';
             display: inline-block;
             width: 16px;
             height: 14px;
             margin-right: 5px;
             background: url("/static/frontend/images/icons/header/view_all_departments_blue.svg") center center no-repeat;
             background-size: contain;
         }
    </style>
{/block}
