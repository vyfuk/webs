<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Fykosak\Utils\BaseComponent\BaseComponent;

final class Jumbotron extends BaseComponent
{
    public static function render(BootstrapNavBar $secondMenu, string $lang = 'cs'): string
    {
        $items = self::getItemsByPage($lang);
        if (!isset($items)) {
            return '
<div class="container-fluid header mb-3">
    <div class="row nav-container hidden-md-down second-nav">' . $secondMenu->render() . '</div>
</div>';
        }
        $id = uniqid();
        $indicators = [];
        $parsedItems = [];
        foreach ($items as $key => $item) {
            $indicators[] = '<li data-target="#' . $id . '" data-slide-to="' . $key . '"></li>';
            $parsedItems[] = self::getCarouselItem($item, !$key);
        }

        return '
<div class="container-fluid header-image jumbotron">
    <div class="carousel-container">
        <div id="' . $id . '" class="feed-carousel carousel slide mb-3" data-ride="carousel">
            <ol class="carousel-indicators">' . join('', $indicators) . '</ol>
            <div class="carousel-inner" role="listbox">' . join('', $parsedItems) . '</div>
        </div>
    </div>
    <div class="row nav-container hidden-md-down second-nav">' . $secondMenu->render() . '</div>
</div>';
    }

    private static function getCarouselItem(JumbotronItem $item, bool $active): string
    {
        $style = null;
        if (is_string($item->backgrounds['outer'])) {
            $style = 'background-image: url(' . ml($item->backgrounds['outer'], ['w' => 1200]) . ')';
        }
        return '
<div class="carousel-item ' .
            ($style ? '' : 'bg-' . $item->backgrounds['inner'] . '-fade ') .
            ($active ? ' active' : '') .
            '" style="' . ($style ?? '') . '">
    <div class="mx-auto col-lg-8 col-xl-5">
        <div class=" jumbotron-inner-container d-block ' .
            ($style ? 'bg-' . $item->backgrounds['inner'] . '-fade ' : '') . '">
            <h1>' . hsc($item->headline) . '</h1>
            <p>' . p_render('xhtml', p_get_instructions($item->text), $info) . '</p>' .
            self::getButtons($item) . '
        </div>
    </div>
</div>';
    }

    private static function getButtons(JumbotronItem $item): string
    {
        $html = '';
        foreach ($item->buttons as $button) {
            $id = $button['page'];
            $html .= '<p><a class="btn btn-outline-secondary" href="' .
                (preg_match('|^https?://|', $id) ? hsc($id) : wl($id, null, true)) . '">' . $button['title'] .
                '</a></p>';
        }
        return $html;
    }

    private static function getDataFromJSON(string $dataPage): ?array
    {

        $data = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $dataPage . '.json'), true);
        if (!$data) {
            return null;
        }
        $items = [];
        foreach ($data as $datum) {
            $items[] = new  JumbotronItem($datum);
        }
        return $items;
    }

    /**
     * @return JumbotronItem[]|null
     */
    public static function getItemsByPage(string $lang): ?array
    {
        switch ($lang) {
            case 'cs':
                return self::getDataFromJSON('jumbotron-data-cs');
            case 'en':
                return self::getDataFromJSON('jumbotron-data-en');
            default:
                return null;
        }
    }
}