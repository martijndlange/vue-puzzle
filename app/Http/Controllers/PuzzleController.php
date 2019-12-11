<?php

namespace App\Http\Controllers;

class PuzzleController extends Controller
{
    public function overview() {
        $result = simplexml_load_file(public_path().'/puzzle.xml');

        $gridWidth = (int)$result->crossword->grid->attributes()->width;
        $gridHeight = (int)$result->crossword->grid->attributes()->height;
        $rawCells = $result->crossword->grid->xpath('cell');
        $rawWords = $result->crossword->xpath('word');
        $rawClues = $result->crossword->clues;
        $cells = [];
        $words = [];
        $clues = [];

        foreach ($rawClues as $clueGroup) {
            $title = (string)$clueGroup->xpath('title/b')[0];
            $arrClue = ($clueGroup->xpath('clue'));
            foreach ($arrClue as $clue) {
                $c = [];
                $c['word'] = (string)$clue;
                $c['format'] = (string)$clue->attributes()->format;
                $c['length'] = (string)$clue->attributes()->word;
                $clues[$title][] = $c;
            }
        }

        foreach ($rawWords as $word) {
            $w = [];
            $w['id'] = (string)$word->attributes()->id;
            $w['x'] = (string)$word->attributes()->x;
            $w['y'] = (string)$word->attributes()->y;
            $words[] = $w;
        }

        foreach ($rawCells as $cell) {
            $c = [];
            $c['x'] = (int)$cell->attributes()->x;
            $c['y'] = (int)$cell->attributes()->y;
            if (!empty($cell->attributes()->solution)) {
                $c['solution'] = (string)$cell->attributes()->solution;
            }
            if (!empty($cell->attributes()->type)) {
                $c['type'] = (string)$cell->attributes()->type;
                if ((string)$cell->attributes()->type == 'clue') {
                    $c['clue'] = str_replace('\n', ' ', (string)$cell->clue);
                }
            }
            $arrow= $cell->xpath('arrow');
            if (!empty($arrow)) {
                $a = $cell->arrow->attributes();
                $c['arrow'] = ['from' => (string)$a->from, 'to' => (string)$a->to];
            }
            $cells[] = $c;
        }

        $cells = json_encode($cells);
        $words = json_encode($words);
        $clues = json_encode($clues);

        return view('puzzle', compact(
            'gridWidth',
            'gridHeight',
            'cells',
            'clues',
            'words'
        ));
    }
}
