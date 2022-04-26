<?php

class XmlToCsvConverter
{
	public function convert(string $fileInput, string $fileOutput): bool
	{
		if (gettype(simplexml_load_file($fileInput)) == 'boolean') {
			throw new Exception('Error! File ' . $fileInput . ' not found!');
		}
		$xmlFile = simplexml_load_file($fileInput);
		$data = [];
		$data[] = $this->buildCsvHeader($xmlFile);

		foreach ($xmlFile as $record) {
			$data[] = get_object_vars($record);
		}

		$this->writeToCsv($fileOutput, $data);

		return true;
	}

	private function buildCsvHeader(SimpleXMLElement|bool $xmlFile): array
	{
		$header = [];

		foreach ($xmlFile as $record) {
			foreach (array_keys(get_object_vars($record)) as $column) {
				$header[] = $column;
			}

			break;
		}

		return $header;
	}

	private function writeToCsv(string $fileOutput, array $data): void
	{
		$file = fopen($fileOutput, 'w');

		foreach ($data as $row) {
			fputcsv($file, $row);
		}

		fclose($file);
	}
}

$converter = new XmlToCsvConverter();
try {
	$converter->convert('dataset.xml', 'dataset.csv');
} catch (Exception $e) {
	echo $e->getMessage();
}

