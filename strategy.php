<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Strategy Pattern</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <?php

  $users = [
    ['name' => 'Alice', 'age' => 30],
    ['name' => 'Bob', 'age' => 25],
    ['name' => 'Charlie', 'age' => 35],
    ['name' => 'Diana', 'age' => 28],
  ];

  $method = $_GET['method'] ?? 'none';

  class SortStrategy
  {
    public static function sort(array $data, string $method, string $key): array
    {
      switch ($method) {
        case 'bubble':
          return self::bubbleSort($data, $key);
        case 'quick':
          return self::quickSort($data, $key);
        case 'merge':
          return self::mergeSort($data, $key);
        default:
          return $data; // Sem ordenação
      }
    }

    private static function bubbleSort(array $data, string $key): array
    {
      $n = count($data);
      for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
          if ($data[$j][$key] > $data[$j + 1][$key]) {
            $temp = $data[$j];
            $data[$j] = $data[$j + 1];
            $data[$j + 1] = $temp;
          }
        }
      }
      return $data;
    }

    private static function quickSort(array $data, string $key): array
    {
      if (count($data) < 2) {
        return $data;
      }
      $pivot = $data[0];
      $left = array_filter(array_slice($data, 1), fn($item) => $item[$key] <= $pivot[$key]);
      $right = array_filter(array_slice($data, 1), fn($item) => $item[$key] > $pivot[$key]);
      return array_merge(self::quickSort($left, $key), [$pivot], self::quickSort($right, $key));
    }

    private static function mergeSort(array $data, string $key): array
    {
      if (count($data) <= 1) {
        return $data;
      }
      $middle = floor(count($data) / 2);
      $left = self::mergeSort(array_slice($data, 0, $middle), $key);
      $right = self::mergeSort(array_slice($data, $middle), $key);
      return self::merge($left, $right, $key);
    }

    private static function merge(array $left, array $right, string $key): array
    {
      $result = [];
      while (count($left) > 0 && count($right) > 0) {
        if ($left[0][$key] <= $right[0][$key]) {
          $result[] = array_shift($left);
        } else {
          $result[] = array_shift($right);
        }
      }
      return array_merge($result, $left, $right);
    }
  }

  $sortedUsers = SortStrategy::sort($users, $method, 'age');
  $currentUrl = strtok($_SERVER['REQUEST_URI'], '?');
  ?>

  <div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl w-full">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
      Strategy Pattern: Ordenação
    </h1>

    <div class="flex justify-center space-x-4 mb-6">
      <form action="<?= htmlspecialchars($currentUrl) ?>" method="get">
        <input type="hidden" name="method" value="bubble">
        <button type="submit" class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600">
          Bubble Sort
        </button>
      </form>
      <form action="<?= htmlspecialchars($currentUrl) ?>" method="get">
        <input type="hidden" name="method" value="quick">
        <button type="submit" class="bg-green-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-600">
          Quick Sort
        </button>
      </form>
      <form action="<?= htmlspecialchars($currentUrl) ?>" method="get">
        <input type="hidden" name="method" value="merge">
        <button type="submit" class="bg-purple-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-purple-600">
          Merge Sort
        </button>
      </form>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-200">
          <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Age</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sortedUsers as $user) : ?>
          <tr>
            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['name']) ?></td>
            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['age']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>

</html>
