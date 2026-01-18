public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Cari Member')
                    ->placeholder('Ketik nama, email, atau no. hp...')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(function (string $search) {
                        return Member::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->limit(10)
                            ->get()
                            ->mapWithKeys(function ($member) {
                                // HTML Tampilan di Dropdown
                                $avatarUrl = $member->picture
                                    ? asset('storage/' . $member->picture)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=random';

                                $html = '
                                    <div class="flex items-center gap-3 py-1">
                                        <img src="' . $avatarUrl . '" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        <div class="flex flex-col text-left">
                                            <span class="font-bold text-gray-800">' . $member->name . '</span>
                                            <span class="text-xs text-gray-500">' . $member->email . ' • ' . ($member->phone ?? '-') . '</span>
                                        </div>
                                    </div>
                                ';
                                return [$member->id => $html];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $member = Member::find($value);
                        return $member ? $member->name : null;
                    })
                    ->allowHtml()
                    ->reactive(),
            ])
            ->statePath('data');
    }