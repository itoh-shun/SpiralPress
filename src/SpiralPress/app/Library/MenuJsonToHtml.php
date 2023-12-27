<?php

namespace SpiralPress\App\Library;

class MenuJsonToHtml {
    
    function __construct() {
    }

    public function render(array $menus) {
        return '<ul class="menu lg:menu-horizontal rounded-box break-keep">' . $this->buildMenuHtml($menus) . '</ul>';
    }

    private function buildMenuHtml(array $menus) {
        $html = '';

        foreach ($menus as $menu) {
            if ($menu['isFolder']) {
                // フォルダの場合（サブメニューを持つ）
                $html .= '<li><details><summary>' . htmlspecialchars($menu['name']) . '</summary>';
                $html .= '<ul>' . $this->buildMenuHtml($menu['children']) . '</ul>'; // 再帰的にサブメニューを処理
                $html .= '</details></li>';
            } else {
                // 通常のメニューアイテムの場合
                $html .= '<li><a href="'.url('post.show',['postShareId' => $menu['postShareId'] ]).'">' . htmlspecialchars($menu['name']) . '</a></li>';
            }
        }

        return $html;
    }
}