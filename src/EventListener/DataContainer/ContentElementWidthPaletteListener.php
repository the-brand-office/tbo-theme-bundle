<?php
declare(strict_types=1);

namespace Tbo\TboThemeBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_content', target: 'config.onload')]
class ContentElementWidthPaletteListener
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(DataContainer $dc = null): void
    {
        if (!$dc || !$dc->id) {
            return;
        }

        $ptable = 'tl_article';
        $pid = 0;
        $sorting = 0;
        
        if ($dc->activeRecord) {
            $ptable = $dc->activeRecord->ptable;
            $pid = (int) $dc->activeRecord->pid;
            $sorting = (int) $dc->activeRecord->sorting;
        } else {
            $row = $this->connection->fetchAssociative("SELECT ptable, pid, sorting FROM tl_content WHERE id = ?", [$dc->id]);
            if ($row) {
                $ptable = $row['ptable'];
                $pid = (int) $row['pid'];
                $sorting = (int) $row['sorting'];
            }
            if (!$ptable) {
                $ptable = 'tl_article';
            }
        }

        // Sichtbar nur innerhalb tl_article
        if ($ptable !== 'tl_article') {
            return;
        }
        
        // Prüfung auf Verschachtelung (ausblenden, wenn das Element z.B. im Slider liegt)
        if ($pid > 0 && $sorting > 0 && $this->checkIsNested($pid, $sorting)) {
            return;
        }

        // Zu allen aktiven Paletten hinzufügen direkt nach dem Typ
        foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $name => $palette) {
            if (is_string($palette) && $name !== '__selector__') {
                if (strpos($palette, 'tbo_element_width') === false) {
                     $newPalette = str_replace(
                         '{type_legend},type', 
                         '{type_legend},type,tbo_element_width', 
                         $palette
                     );
                     
                     // Fallback, wenn type_legend fehlt
                     if ($newPalette === $palette) {
                         $newPalette = 'tbo_element_width,' . $palette;
                     }
                     
                     $GLOBALS['TL_DCA']['tl_content']['palettes'][$name] = $newPalette;
                }
            }
        }
    }

    private function checkIsNested(int $pid, int $sorting): bool
    {
        $stmt = $this->connection->executeQuery(
            "SELECT type FROM tl_content WHERE pid = ? AND sorting < ? ORDER BY sorting DESC",
            [$pid, $sorting]
        );
        $types = $stmt->fetchFirstColumn();

        $nestingCount = 0;
        foreach ($types as $type) {
            $typeLower = strtolower($type);
            $isStart = str_ends_with($typeLower, 'start') || str_contains($typeLower, 'begin');
            $isStop = str_ends_with($typeLower, 'stop') || str_contains($typeLower, 'end');
            
            if ($isStart) {
                $nestingCount++;
            } elseif ($isStop) {
                $nestingCount--;
            }
            if ($nestingCount > 0) {
                return true; 
            }
        }
        return false;
    }
}
