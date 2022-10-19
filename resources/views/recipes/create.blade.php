<x-app-layout>
    <div class="py-12">
        <form method="POST" action="{{ route('recipes.store') }}">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <x-input-label :value="__('Wie heißt das Gericht?')" for="title"></x-input-label>
                            <x-text-input type="text" id="title" class="block mt-1 w-full"></x-text-input>
                        </div>
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Beschreibe es')"></x-input-label>
                            <x-textarea id="description" class="block mt-1 w-full"></x-textarea>
                        </div>
                        <hr class="mb-6">
                        <div class="mb-6">
                            Zutaten:
                            <x-text-input type="text" class="block mt-1 w-full"></x-text-input>
                            <div
                                class="block mt-1 w-full bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle"
                            >+
                            </div>
                        </div>
                        <div class="mb-6">
                            Schritte:
                            <x-textarea class="block mt-1 w-full"></x-textarea>
                            <div
                                class="block mt-1 w-full bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                +
                            </div>
                        </div>
                        <div>
                            Bilder:
                            <div class="flex justify-between w-full">
                                <div
                                    class="block mt-1 w-32 bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                    +
                                </div>
                                <div
                                    class="block mt-1 w-32 bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                    +
                                </div>
                                <div
                                    class="block mt-1 w-32 bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                    +
                                </div>
                                <div
                                    class="block mt-1 w-32 bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                    +
                                </div>
                                <div
                                    class="block mt-1 w-32 bg-black hover:bg-red-600 text-white justify-items-center text-center h-8 align-middle">
                                    +
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
