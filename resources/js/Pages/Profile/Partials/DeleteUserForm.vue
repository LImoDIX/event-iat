<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-8">
        <div class="bg-white border border-gray-300 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-black">Удаление аккаунта</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        <p>
                            После удаления вашей учетной записи все ее ресурсы и данные будут
                            удалены без возможности восстановления. Перед удалением учетной записи
                            загрузите все данные или информацию, которые вы хотите сохранить.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                @click="confirmUserDeletion"
                class="px-6 py-3 bg-black text-white rounded-lg font-semibold shadow-md transition duration-200 hover:bg-gray-800"
            >
                Удалить аккаунт
            </button>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 bg-white border border-gray-300 rounded-2xl max-w-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-lg font-medium text-black">
                            Вы уверены?
                        </h2>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-600">
                    После удаления вашей учетной записи все ее ресурсы и данные
                    будут удалены без возможности восстановления. Пожалуйста, введите
                    свой пароль, чтобы подтвердить, что вы хотите навсегда удалить
                    свою учетную запись.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Пароль"
                        class="block text-sm font-medium text-gray-700"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Введите ваш пароль"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="closeModal"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-black hover:bg-gray-100 transition"
                    >
                        Отмена
                    </button>

                    <button
                        class="px-4 py-2 bg-black text-white rounded-lg font-semibold shadow-md transition duration-200 hover:bg-gray-800"
                        :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        <span v-if="form.processing" class="mr-2 flex items-center">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        Удалить аккаунт
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
