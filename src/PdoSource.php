<?php

namespace BrowscapHelper\Source;

use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DirectorySource
 *
 * @author  Thomas Mueller <mimmi20@live.de>
 */
class PdoSource implements SourceInterface
{
    /**
     * @var \PDO
     */
    private $pdo = null;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param \Monolog\Logger                                   $logger
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param int                                               $limit
     *
     * @return \Generator
     */
    public function getUserAgents(Logger $logger, OutputInterface $output, $limit = 0)
    {
        $sql = 'SELECT DISTINCT SQL_BIG_RESULT HIGH_PRIORITY `agent` FROM `agents` ORDER BY `lastTimeFound` DESC, `count` DESC, `idAgents` DESC';

        if ($limit) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $driverOptions = [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY];

        /** @var \PDOStatement $stmt */
        $stmt = $this->pdo->prepare($sql, $driverOptions);
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            yield trim($row->agent);
        }
    }

    /**
     * @param \Monolog\Logger                                   $logger
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Generator
     */
    public function getTests(Logger $logger, OutputInterface $output)
    {
        $sql = 'SELECT DISTINCT SQL_BIG_RESULT HIGH_PRIORITY `agent` FROM `agents` ORDER BY `lastTimeFound` DESC, `count` DESC, `idAgents` DESC';

        if ($limit) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        $driverOptions = [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY];

        /** @var \PDOStatement $stmt */
        $stmt = $this->pdo->prepare($sql, $driverOptions);
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $agent = trim($row->agent);

            yield [$agent => [
                'ua'         => $agent,
                'properties' => [
                    'Browser_Name'            => null,
                    'Browser_Type'            => null,
                    'Browser_Bits'            => null,
                    'Browser_Maker'           => null,
                    'Browser_Modus'           => null,
                    'Browser_Version'         => null,
                    'Platform_Codename'       => null,
                    'Platform_Marketingname'  => null,
                    'Platform_Version'        => null,
                    'Platform_Bits'           => null,
                    'Platform_Maker'          => null,
                    'Platform_Brand_Name'     => null,
                    'Device_Name'             => null,
                    'Device_Maker'            => null,
                    'Device_Type'             => null,
                    'Device_Pointing_Method'  => null,
                    'Device_Dual_Orientation' => null,
                    'Device_Code_Name'        => null,
                    'Device_Brand_Name'       => null,
                    'RenderingEngine_Name'    => null,
                    'RenderingEngine_Version' => null,
                    'RenderingEngine_Maker'   => null,
                ],
            ]
            ];
        }
    }
}
