<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Termwind\{render, ask, terminal};

#[AsCommand(
    name: 'app:termwind-showcase',
    description: 'Showcase of Termwind capabilities',
)]
class TermwindShowcaseCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // Simple heading and text
        render(<<<'HTML'
            <div class="py-1">
                <div class="px-1 bg-blue-500 text-white font-bold">Termwind Showcase</div>
                <div class="px-1 mt-1">This command demonstrates various <span class="text-green-500">Termwind</span> capabilities.</div>
            </div>
        HTML);

        // Table example
        render(<<<'HTML'
            <div class="mt-1">
                <div class="px-1 bg-yellow-500 font-bold">Data Table Example</div>
                <table>
                    <thead>
                        <tr>
                            <th class="px-1 bg-blue-600 text-white">ID</th>
                            <th class="px-1 bg-blue-600 text-white">Name</th>
                            <th class="px-1 bg-blue-600 text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-1">1</td>
                            <td class="px-1">User Alpha</td>
                            <td class="px-1 text-green-500">Active</td>
                        </tr>
                        <tr>
                            <td class="px-1">2</td>
                            <td class="px-1">User Beta</td>
                            <td class="px-1 text-red-500">Inactive</td>
                        </tr>
                        <tr>
                            <td class="px-1">3</td>
                            <td class="px-1">User Gamma</td>
                            <td class="px-1 text-yellow-500">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        HTML);

        // Progress bar example
        render(<<<'HTML'
            <div class="mt-1">
                <div class="px-1 bg-purple-500 text-white font-bold">Progress Bar Example</div>
                <div class="flex mt-1">
                    <div class="px-1 bg-green-500">25%</div>
                </div>
                <div class="flex mt-1">
                    <div class="px-1 bg-blue-500">50%</div>
                </div>
                <div class="flex mt-1">
                    <div class="px-1 bg-yellow-500">75%</div>
                </div>
                <div class="flex mt-1">
                    <div class="px-1 bg-red-500">100%</div>
                </div>
            </div>
        HTML);

        // Alert boxes
        render(<<<'HTML'
            <div class="mt-1">
                <div class="px-1 bg-gray-500 text-white font-bold">Alert Boxes</div>
                <div class="px-1 mt-1 bg-blue-100 text-blue-800">
                    <strong>Info:</strong> This is an information alert.
                </div>
                <div class="px-1 mt-1 bg-green-100 text-green-800">
                    <strong>Success:</strong> Operation completed successfully!
                </div>
                <div class="px-1 mt-1 bg-yellow-100 text-yellow-800">
                    <strong>Warning:</strong> Proceed with caution.
                </div>
                <div class="px-1 mt-1 bg-red-100 text-red-800">
                    <strong>Error:</strong> Something went wrong!
                </div>
            </div>
        HTML);

        // Interactive element - ask for input
        render(<<<'HTML'
            <div class="py-1 mt-1">
                <div class="px-1 bg-teal-500 text-white font-bold">Interactive Example</div>
                <div class="px-1 mt-1">Termwind can also handle user input:</div>
            </div>
        HTML);

        $name = ask('<span class="text-green-500">Enter your name (or press Enter to skip): </span>');
        
        if (!empty($name)) {
            render(sprintf(
                '<div class="px-1 mt-1 bg-green-100 text-green-800">Hello, <span class="font-bold">%s</span>! Nice to meet you!</div>',
                htmlspecialchars($name)
            ));
        } else {
            render('<div class="px-1 mt-1 text-gray-500">No name entered, that\'s okay!</div>');
        }

        // Terminal size information
        $size = terminal()->width() . 'x' . terminal()->height();
        
        render(<<<HTML
            <div class="py-1 mt-1">
                <div class="px-1 bg-indigo-500 text-white font-bold">Terminal Information</div>
                <div class="px-1 mt-1">Your terminal size is: <span class="text-blue-500 font-bold">$size</span></div>
            </div>
        HTML);

        // Closing
        render(<<<'HTML'
            <div class="py-1 mt-1">
                <div class="px-1 bg-green-500 text-white font-bold">Thank You!</div>
                <div class="px-1 mt-1">
                    This concludes the Termwind showcase. For more information, check out:
                    <div class="text-blue-500 underline">https://github.com/nunomaduro/termwind</div>
                </div>
            </div>
        HTML);

        return Command::SUCCESS;
    }
} 