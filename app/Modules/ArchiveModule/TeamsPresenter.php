<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

use App\Components\TeamList\TeamListComponent;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Teams')));
    }

    /**
     * @return TeamListComponent
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentTeamList(): TeamListComponent
    {
        return new TeamListComponent($this->getContext(), $this->getEvent()->eventId);
    }
}