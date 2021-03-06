<?php
////////////////////////////////////////////////////////////////////////////////
// __________ __             ________                   __________
// \______   \  |__ ______  /  _____/  ____ _____ ______\______   \ _______  ___
//  |     ___/  |  \\____ \/   \  ____/ __ \\__  \\_  __ \    |  _//  _ \  \/  /
//  |    |   |   Y  \  |_> >    \_\  \  ___/ / __ \|  | \/    |   (  <_> >    <
//  |____|   |___|  /   __/ \______  /\___  >____  /__|  |______  /\____/__/\_ \
//                \/|__|           \/     \/     \/             \/            \/
// -----------------------------------------------------------------------------
//          Designed and Developed by Brad Jones <brad @="bjc.id.au" />
// -----------------------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////

/*
 * Include our local composer autoloader just in case
 * we are called with a globally installed version of robo.
 */
require_once(__DIR__.'/vendor/autoload.php');

use Gears\String\Str;
use YaLinqo\Enumerable as Linq;
use Symfony\Component\Yaml\Yaml;
use phpDocumentor\Reflection\DocBlockFactory;

class RoboFile extends Robo\Tasks
{
    /**
     * Runs unit tests, with code coverage report.
     */
    public function test()
    {
        exit
        (
            $this->taskPHPUnit()
            ->arg('./tests')
            ->option('coverage-clover', './build/logs/clover.xml')
            ->run()->getExitCode()
        );
    }

    /**
     * Generates the projects documentation.
     *
     * This is made up of reflected information, DocBlocks
     * and other human written text.
     */
    public function docGenerate()
    {
        // This will be populated as we loop through all our methods.
        // At the end of the process we will write this to the
        // couscous.yml config file.
        $couscousMenuItems = [];

        // We are using some code from phpDocumentor to parse the docblocks
        // and keep the markdown documentation up to date with the code.
        // https://github.com/phpDocumentor/ReflectionDocBlock
        $docBlockParser = DocBlockFactory::createInstance();

        // To actually generate the method mardown documents we are using the
        // [Foil](http://www.foilphp.it/) view engine. The template for all
        // documents output in `./docs/Methods` is `./docs/_Views/method.php`
        $view = Foil\engine
        ([
            'folders' => ['./docs/_Views'],
            'autoescape' => false
        ]);

        // Loop over all methods contained in traits of the Str class.
        Linq::from((new ReflectionClass('\\Gears\\String\\Str'))->getTraits())
        ->selectMany(function($v){ return $v->getMethods(); })
        ->each(function($rMethod) use (&$couscousMenuItems, $docBlockParser, $view)
        {
            // Parse the methods docblock
            $docBlock = $docBlockParser->create($rMethod->getDocComment());

            // Create an array of method parameters
            $parameters = [];
            $paramTags = Linq::from($docBlock->getTagsByName('param'));
            foreach ($rMethod->getParameters() as $rParam)
            {
                $paramTag = $paramTags->firstOrDefault(null, function($v) use ($rParam)
                {
                    return $v->getVariableName() == $rParam->getName();
                });

                $parameters[] =
                [
                    'name' => $rParam->getName(),
                    'type' => $paramTag !== null ? (string)$paramTag->getType() : '',
                    'default' => $rParam->isOptional() ? $rParam->getDefaultValue() : '',
                    'description' => $paramTag !== null ? (string)$paramTag->getDescription() : ''
                ];
            }

            // Grab the return tag, if there is one.
            $returnTag = Linq::from($docBlock->getTagsByName('return'))->firstOrDefault(null);

            // This is where the human element comes into play.
            // Each method may/should have an additional markdown document that
            // gets merged into the generated document. This additional document
            // should contain any examples, a changelog specfic to the method,
            // and any other note worthy information, etc.
            $mergeDoc = '';
            $mergeDocFile = './docs/Methods/'.$rMethod->getName().'.merge.md';
            if (file_exists($mergeDocFile))
            {
                $mergeDoc = file_get_contents($mergeDocFile);
            }

            // Finally render the method documentation.
            file_put_contents('./docs/Methods/'.$rMethod->getName().'.md', $view->render('method',
            [
                'method' =>
                [
                    'name' => $rMethod->getName(),
                    'summary' => (string)$docBlock->getSummary(),
                    'description' => (string)$docBlock->getDescription(),
                    'parameters' => $parameters,
                    'return' =>
                    [
                        'type' => $returnTag !== null ? $returnTag->getType() : '',
                        'description' => $returnTag !== null ? $returnTag->getDescription() : ''
                    ],
                    'merge' => $mergeDoc
                ]
            ]));

            // Add the method to our couscous menu.
            $couscousMenuItems[$rMethod->getName()] =
            [
                'text' => $rMethod->getName(),
                'relativeUrl' => 'docs/Methods/'.Str::s($rMethod->getName())->toLowerCase().'.html'
            ];
        });

        // Save the updated couscous menu.
        $couscous = Yaml::parse(file_get_contents('couscous.yml'));
        $couscous['menu']['sections']['methods']['items'] = $couscousMenuItems;
        file_put_contents('couscous.yml', Yaml::dump($couscous, 100));

        // Start the couscous preview server
        $this->_exec(__DIR__.'/vendor/bin/couscous preview');
    }
}
