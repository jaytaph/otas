<?php

namespace CliOtas\Command;

use Otas\Engine\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/* Displays textual map of the given adventure */

class MapCommand extends Command {

    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;

    protected function configure()
    {
        $this
            ->setName('debug:map')
            ->setDescription('Create a map from the adventure')
            ->setDefinition(array(
                new InputArgument('dir', InputArgument::REQUIRED, 'The directory containing the adventure'),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $dir = $input->getArgument('dir');

        // Load main.yml
        $file = $dir."/main.yml";
        $yaml = Yaml::load($file);

        $scenes = array();

        // Load all scenes
        $stack = array();
        $scene = $yaml['entry_scene'];
        array_push($stack, $scene);

        while (count($stack)) {
            $scene = array_pop($stack);
            $yaml = Yaml::load($dir .'/'.$scene . '.yml');

            $scenes[$scene] = array('key' => $scene, 'title' => $yaml['scene']['title'], 'exit' => array(
                'north' => false,
                'east' => false,
                'south' => false,
                'west' => false,
            ));

            foreach ($yaml['scene']['exit'] as $direction => $exit_scene) {
                $scenes[$scene]['exit'][strtolower($direction)] = $exit_scene;
                if (! array_key_exists($exit_scene, $scenes)) {
                    array_push($stack, $exit_scene);
                }
            }
        }

        // Validate links
        foreach ($scenes as $scene) {
            if (count($scene['exit']) == 0) {
                throw new \Exception(sprintf("Scene '%s' does not have any exit"));
            }

            foreach ($scene['exit'] as $direction => $exit_scene) {
                if ($exit_scene == false) continue;

                switch ($direction) {
                    case 'north' :
                        if ($scenes[$exit_scene]['exit']['south'] != $scene['key']) {
                            throw new \Exception(sprintf("Scene '%s' exit North does match scene '%s' exit South", $scene['key'], $exit_scene));
                        }
                        break;
                    case 'south':
                        if ($scenes[$exit_scene]['exit']['north'] != $scene['key']) {
                            throw new \Exception(sprintf("Scene '%s' exit South does match scene '%s' exit North", $scene['key'], $exit_scene));
                        }
                        break;
                    case 'east' :
                        if ($scenes[$exit_scene]['exit']['west'] != $scene['key']) {
                            throw new \Exception(sprintf("Scene '%s' exit East does match scene '%s' exit West", $scene['key'], $exit_scene));
                        }
                        break;
                    case 'west' :
                        if ($scenes[$exit_scene]['exit']['east'] != $scene['key']) {
                            throw new \Exception(sprintf("Scene '%s' exit West does match scene '%s' exit East", $scene['key'], $exit_scene));
                        }
                        break;
                }
            }
        }



        $scene = reset($scenes);

        $stack = array();
        array_push($stack, array($scene, 0, 0));

        $tmp = array();
        $tmp['title'] = $scene['title'];
        $tmp['exit'] = "";
        foreach ($scene['exit'] as $d => $e) {
            if (! $e) continue;
            $tmp['exit'] .= strtoupper($d[0]);
        }

        // Add initial scene
        $a = array();

        while (count($stack)) {
            list($scene, $x, $y) = array_pop($stack);

            if (! isset($a[$y])) $a[$y] = array();

            if (isset($a[$y][$x])) continue;

            $dirs = "";
            foreach ($scene['exit'] as $dir => $exit) {
                if ($exit === false) continue;

                switch (strtoupper($dir[0])) {
                    case 'N' :
                        $dirs .= "N";
                        array_push($stack, array($scenes[$exit], $x, $y - 1));
                        break;
                    case 'E' :
                        $dirs .= "E";
                        array_push($stack, array($scenes[$exit], $x + 1, $y));
                        break;
                    case 'S' :
                        $dirs .= "S";
                        array_push($stack, array($scenes[$exit], $x, $y + 1));
                        break;
                    case 'W' :
                        $dirs .= "W";
                        array_push($stack, array($scenes[$exit], $x - 1, $y));
                        break;
                }
            }

            $a[$y][$x] = array($scene['title'], $dirs);
        }


        foreach (array_keys($a) as $k) {
            ksort($a[$k]);
        }
        ksort($a);

        $max = max(array_map(function($row){
            return count($row);
        }, $a));
        $min = min(array_map(function($row){
            return array_keys($row)[0];
        }, $a));
        $max = $min + $max;

        foreach (array_keys($a) as $k) {
            for ($i=$min; $i!=$max; $i++) {
                if (! isset($a[$k][$i])) $a[$k][$i] = false;
            }
            ksort($a[$k]);
        }





        define('NODE_WIDTH', 30);
        define('NODE_HEIGHT', 5);
        define('EDGE_WIDTH', 4);
        define('EDGE_HEIGHT', 2);

        $rows = count($a) * (NODE_HEIGHT + EDGE_HEIGHT);
        $columns = count($a[0]);
        $buf = array();
        for ($i=0; $i!=$rows; $i++) {
            $buf[] = str_repeat(" ", (NODE_WIDTH + EDGE_WIDTH) * $columns);
        }

        $y = 0;
        foreach ($a as $row) {
            $x = 0;
            foreach ($row as $column) {
                if ($column) {
                    $this->print_node($buf, $x * (NODE_WIDTH + EDGE_WIDTH), $y * (NODE_HEIGHT + EDGE_HEIGHT), $column[0]);
                    for ($i = 0; $i != strlen($column[1]); $i++) {
                        $this->print_edge($buf, $x * (NODE_WIDTH + EDGE_WIDTH), $y * (NODE_HEIGHT + EDGE_HEIGHT), $column[1][$i]);
                    }
                }
                $x++;
            }
            $y++;
        }

        foreach ($buf as $line) {
            $this->output->writeln("<comment>$line</comment>");
        }

    }

    function print_edge(&$buf, $x, $y, $dir) {
        $dx = floor((NODE_WIDTH - 2) / 2) + 1;
        $dy = floor((NODE_HEIGHT - 2) / 2) + 1;

        switch ($dir) {
            case 'N' :
                for ($i=0; $i!=EDGE_HEIGHT; $i++) {
                    $this->plot($buf, $x + $dx, $y - $i - 1, "|");
                }
                $this->plot($buf, $x + $dx, $y + 0, "N");
                break;
            case 'W' :
                $this->plot($buf, $x + 0, $y + $dy, "W");

                for ($i=0; $i!=EDGE_WIDTH; $i++) {
                    $this->plot($buf, $x - $i - 1, $y + $dy, "-");
                }
                break;
            case 'S' :
                $this->plot($buf, $x + $dx, $y + NODE_HEIGHT - 1, "S");

                for ($i=0; $i!=EDGE_HEIGHT; $i++) {
                    $this->plot($buf, $x + $dx, $y + NODE_HEIGHT + $i, "|");
                }
                break;
            case 'E' :
                $this->plot($buf, $x + NODE_WIDTH - 1, $y + $dy, "E");

                for ($i=0; $i!=EDGE_WIDTH; $i++) {
                    $this->plot($buf, $x + NODE_WIDTH  + $i, $y + $dy, "-");
                }

                break;
        }

    }

    function print_node(&$buf, $x, $y, $title) {
        // Top
        $this->plot($buf, $x, $y + 0, "+".str_repeat('-', NODE_WIDTH-2)."+");

        // Sides
        for ($i=0; $i!=NODE_HEIGHT-2; $i++) {
            $this->plot($buf, $x, $y + $i + 1, "|");
            $this->plot($buf, $x + NODE_WIDTH - 1, $y + $i + 1, "|");
        }

        // Bottom
        $this->plot($buf, $x, $y + NODE_HEIGHT - 1, "+".str_repeat('-', NODE_WIDTH-2)."+");

        // Label
        $this->plot($buf, $x + 1, $y + floor((NODE_HEIGHT - 2) / 2) + 1, substr(str_pad($title, NODE_WIDTH-2, ' ', STR_PAD_BOTH), 0, NODE_WIDTH-2-2));
    }

    function plot(&$buf, $x, $y, $s) {
        if (! isset($buf[$y])) return;

        for ($i=0; $i!=strlen($s); $i++) {
            if (strlen($buf[$y]) <= ($x + $i)) continue;
            if (($x + $i) < 0) continue;

            $c = $s[$i];
            $buf[$y][(int)($x + $i)] = $c;
        }
    }


}
