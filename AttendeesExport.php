<?php

namespace Pensoft\Tdwgform;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendeesExport implements FromCollection, WithHeadingRow, WithHeadings
{

	private $collection;

	public function __construct(Collection $collection)
	{
		$this->collection = $collection;
	}

	public function headings(): array
	{
		return [
			'ID',
			'Name',
			'Name tag (first and last names)',
			'Affiliation, Position',
			'Email',
			'Phone',
			'Address',
			'Emergency contact',
			'Comments',
			'Ticket type',
			'Member discount code',
			'Discount',
			'Accompanying person',
			'Help others',
			'Payment',
			'Twitter',
			'Email when using Slack or Discord',
			'I plan to submit an abstract',
			'I plan to attend the Welcome reception on 16 October 2022',
			'I plan to attend the excursion to Rila Monastery on Wednesday 19 October 2022',
			'I plan to attend the conference banquet on Thursday  20 October 2022',
			'I agree to be contacted by event Supporters post-conference.',
			'I understand the fee for anyone I add to accompany me covers the welcome reception, excursion to Rila monastery and banquet.',
			'I\'m willing to chair /  moderate a general open session at TDWG2022',
			'I agree for my name, affiliation, and email to be shared after the conference with other attendees',
		];
	}

	public function collection()
    {
        return $this->collection;
    }
}
