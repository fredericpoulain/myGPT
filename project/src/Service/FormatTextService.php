<?php

namespace App\Service;

class FormatTextService
{
    public function formatText(string $text, $parsedown): string
    {

        $formattedText = $this->addTagsPreAndCode($text);
        // Markdown to html
        $html = $this->convertMarkdownToHtml($formattedText, $parsedown);
        // Conserver les sauts de ligne
        return nl2br($html);
    }

    private function addTagsPreAndCode($text): string
    {
//        $patternTripleBackticks = '/```(php|java|javascript|html|css|typescript|js|jsx|python|c|cpp|ruby|swift|perl|rust|go|kotlin|scala|haskell|sql|r|pp|julia|lojure|groovy|elixir|bash|lua|dart|shell|assembly|objective-c
//)?(.*?)```/s';
        $patternTripleBackticks = '/```([a-zA-Z+-]+)?(.*?)```/s';


        // Remplacement des blocs de code par des balises <pre><code>
        $replacementTripleBackticks = '<pre class="theme-atom-one-dark"><code>$2</code></pre>';

        // Application de l'expression régulière pour les triples backticks
        $formattedText = preg_replace($patternTripleBackticks, $replacementTripleBackticks, $text);

        // Expression régulière pour détecter les petits blocs de code entourés de simples backticks
        $patternSingleBackticks = '/`([^`]+)`/';

        // Remplacement des petits blocs de code par des balises <span>
        $replacementSingleBackticks = '<span class="codeSnippet">$1</span>';

        // Application de l'expression régulière pour les simples backticks
        return preg_replace($patternSingleBackticks, $replacementSingleBackticks, $formattedText);
    }

    private function convertMarkdownToHtml(string $text, $parsedown): string
    {
        return $parsedown->text($text);
    }
}