<?php

$array = [
    ['id' => 1, 'date' => "12.01.2020", 'name' => "test1"],
    ['id' => 2, 'date' => "02.05.2020", 'name' => "test2"],
    ['id' => 4, 'date' => "08.03.2020", 'name' => "test4"],
    ['id' => 1, 'date' => "22.01.2020", 'name' => "test1"],
    ['id' => 2, 'date' => "11.11.2020", 'name' => "test4"],
    ['id' => 3, 'date' => "06.06.2020", 'name' => "test3"]
];

// 1. выделить уникальные записи (убрать дубли) в отдельный массив. в конечном массиве не должно быть элементов с одинаковым id.

function deleteDuplicates(array $array, string $uniqueColumn = 'id'): array
{
    $uniqueIds = array_unique(array_column($array, $uniqueColumn));
    $uniqueItemsKeys = array_keys($uniqueIds);
    return array_values(array_filter($array, fn ($key) => in_array($key, $uniqueItemsKeys), ARRAY_FILTER_USE_KEY));
}

//var_dump(deleteDuplicates($array));

// 2. отсортировать многомерный массив по ключу (любому)

function sortByKey(array $array, string $key, ?callable $modificateValue = null): array
{
    if (is_null($modificateValue)) {
        $modificateValue = fn ($value) => $value;
    }

    usort($array, function ($a, $b) use ($key, $modificateValue) {
        $valueA = $modificateValue($a[$key]);
        $valueB = $modificateValue($b[$key]);
        if ($valueA > $valueB) {
            return 1;
        } else if ($valueA < $valueB) {
            return -1;
        }
        return 0;
    });
    return $array;
}

//var_dump(sortByKey($array, 'id'));
//var_dump(sortByKey($array, 'date', fn ($value) => strtotime($value)));

// 3. вернуть из массива только элементы, удовлетворяющие внешним условиям (например элементы с определенным id)

function filter(array $array, string $key, mixed $value): array
{
    return array_filter($array, fn ($item) => $item[$key] === $value);
}

//var_dump(filter($array, 'name', 'test4'));

// 4. изменить в массиве значения и ключи (использовать name => id в качестве пары ключ => значение)

function flip(array $array, string $key, string $value): array
{
    $keys = array_column($array, $key);
    $values = array_column($array, $value);
    return array_combine($keys, $values);
}
// есть побочное действие - затираются элементы с одинаковыми ключами, остается последний :)
//var_dump(flip($array, 'name', 'id'));

// 5. В базе данных имеется таблица с товарами goods (id INTEGER, name TEXT), 
// таблица с тегами tags (id INTEGER, name TEXT) 
// и таблица связи товаров и тегов goods_tags (tag_id INTEGER, goods_id INTEGER, UNIQUE(tag_id, goods_id)). 
// Выведите id и названия всех товаров, которые имеют все возможные теги в этой базе.

$sqlQuery = "
    SELECT goods.id AS id, goods.name AS name
    FROM goods_tags
    INNER JOIN goods ON goods.id = goods_tags.good_id
    INNER JOIN tags ON tags.id = goods_tags.tag_id
    GROUP BY goods.id HAVING COUNT(tags) = (SELECT COUNT(*) FROM tags)
";

// 6. Выбрать без join-ов и подзапросов все департаменты, в которых есть мужчины, 
// и все они (каждый) поставили высокую оценку (строго выше 5).

$sql = "
    SELECT department_id
    FROM evaluations
    WHERE gender=true 
    GROUP BY department_id HAVING MIN(value) > 5
";
