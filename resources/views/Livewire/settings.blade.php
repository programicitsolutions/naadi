{{-- PATH: resources/views/livewire/settings.blade.php --}}

<div class="min-h-screen bg-gray-950 text-white p-4 pb-20">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-purple-400">⚙️ Settings</h1>
        <p class="text-xs text-gray-500">Customize your NeuroFocus</p>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if($saved)
    <div class="bg-green-600 rounded-xl p-3 mb-4 text-sm font-bold">
        ✅ Settings saved successfully!
    </div>
    @endif

    {{-- PROFILE SETTINGS --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800 mb-4">
        <p class="text-sm font-bold text-purple-400 mb-4">👤 Profile</p>

        <div class="mb-4">
            <label class="text-xs text-gray-500 mb-1 block">Full Name</label>
            <input wire:model="name" type="text"
                class="w-full bg-gray-800 border border-gray-700 rounded-xl
                       px-4 py-3 text-white text-sm focus:outline-none
                       focus:border-purple-500 transition"
                placeholder="Your name" />
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="text-xs text-gray-500 mb-1 block">Email</label>
            <input wire:model="email" type="email"
                class="w-full bg-gray-800 border border-gray-700 rounded-xl
                       px-4 py-3 text-white text-sm focus:outline-none
                       focus:border-purple-500 transition"
                placeholder="your@email.com" />
            @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="text-xs text-gray-500 mb-1 block">Age</label>
            <input wire:model="age" type="number"
                class="w-full bg-gray-800 border border-gray-700 rounded-xl
                       px-4 py-3 text-white text-sm focus:outline-none
                       focus:border-purple-500 transition"
                placeholder="Your age" min="5" max="100" />
        </div>
    </div>

    {{-- MODE SETTINGS --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800 mb-4">
        <p class="text-sm font-bold text-purple-400 mb-4">🎯 Device Mode</p>
        <p class="text-xs text-gray-600 mb-4">
            Choose how NeuroFocus monitors you
        </p>

        @foreach([
            'focus'      => ['icon' => '🎯', 'label' => 'Focus Mode',
                             'desc' => 'Max productivity & distraction blocking'],
            'moving_on'  => ['icon' => '💔', 'label' => 'Moving On Mode',
                             'desc' => 'Heartbreak recovery tracker 😄'],
            'sleep'      => ['icon' => '😴', 'label' => 'Sleep Mode',
                             'desc' => 'Night time recovery monitoring'],
            'meditation' => ['icon' => '🧘', 'label' => 'Meditation Mode',
                             'desc' => 'Calm & mindfulness tracking'],
        ] as $value => $option)
        <div wire:click="$set('mode', '{{ $value }}')"
             class="flex items-center gap-3 p-3 rounded-xl mb-2 cursor-pointer
                    border transition
                    {{ $mode === $value
                        ? 'border-purple-500 bg-purple-900/30'
                        : 'border-gray-800 bg-gray-800/30 hover:border-gray-600' }}">
            <span class="text-2xl">{{ $option['icon'] }}</span>
            <div class="flex-1">
                <p class="text-sm font-bold text-white">{{ $option['label'] }}</p>
                <p class="text-xs text-gray-500">{{ $option['desc'] }}</p>
            </div>
            @if($mode === $value)
                <span class="text-purple-400 text-lg">✓</span>
            @endif
        </div>
        @endforeach
    </div>

    {{-- DEVICE INFO --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800 mb-6">
        <p class="text-sm font-bold text-purple-400 mb-4">🔌 Device Status</p>
        <div class="flex justify-between items-center py-2
                    border-b border-gray-800">
            <span class="text-xs text-gray-400">ESP32 Connection</span>
            <span class="text-xs font-bold text-yellow-400">⏳ Waiting for device</span>
        </div>
        <div class="flex justify-between items-center py-2
                    border-b border-gray-800">
            <span class="text-xs text-gray-400">AD8232 Sensor</span>
            <span class="text-xs font-bold text-yellow-400">⏳ Not connected</span>
        </div>
        <div class="flex justify-between items-center py-2">
            <span class="text-xs text-gray-400">MAX30102 Sensor</span>
            <span class="text-xs font-bold text-yellow-400">⏳ Not connected</span>
        </div>
        <p class="text-xs text-gray-600 mt-3 text-center">
            Devices arriving by 2nd June 📦
        </p>
    </div>

    {{-- SAVE BUTTON --}}
    <button wire:click="save"
        class="w-full bg-purple-600 hover:bg-purple-700 text-white
               font-bold py-4 rounded-xl transition text-sm">
        💾 Save Settings
    </button>

</div>