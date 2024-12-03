<?php
interface ReportInterface {
    public function generate(): string;
}

class BasicReport implements ReportInterface {
    public function generate(): string {
        return "<div class='p-4'>Lorem ipsum dolor sit amet consectetur, adipisicing elit. In pariatur voluptatem quibusdam assumenda ducimus sint perferendis rem consequuntur expedita exercitationem! Iste optio vel deleniti est commodi amet magni, cumque soluta.</div>";
    }
}

abstract class ReportDecorator implements ReportInterface {
    protected ReportInterface $report;

    public function __construct(ReportInterface $report) {
        $this->report = $report;
    }

    public function generate(): string {
        return $this->report->generate();
    }
}

// Adiciona Cabeçalho
class HeaderDecorator extends ReportDecorator {
    private string $headerText;

    public function __construct(ReportInterface $report, string $headerText) {
        parent::__construct($report);
        $this->headerText = $headerText;
    }

    public function generate(): string {
        return "<div class='font-bold text-xl mb-4'>{$this->headerText}</div>" . parent::generate();
    }
}

// Adiciona borda
class BorderDecorator extends ReportDecorator {
    public function generate(): string {
        return "<div class='border-2 border-gray-300 p-4'>" . parent::generate() . "</div>";
    }
}

//Muda fundo
class ColorDecorator extends ReportDecorator {
    private string $bgColor;

    public function __construct(ReportInterface $report, string $bgColor) {
        parent::__construct($report);
        $this->bgColor = $bgColor;
    }

    public function generate(): string {
        return "<div class='bg-{$this->bgColor}'>" . parent::generate() . "</div>";
    }
}

// Geração do relatório com decorators
$report = new BasicReport();
$report = new HeaderDecorator($report, "Relatório Financeiro");
$report = new BorderDecorator($report);
$report = new ColorDecorator($report, "blue-100");

$output = $report->generate();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-lg w-full">
        <?php echo $output; ?>
    </div>
</body>
</html>
