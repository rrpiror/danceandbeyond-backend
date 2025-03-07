<template>
	<div>
		<label class="block text-sm font-medium text-gray-700">{{ field.name }}</label>
		<select multiple v-model="selectedOptions" @change="handleChange">
			<option v-for="(name, id) in field.options" :key="id" :value="id">{{ name }}</option>
		</select>
	</div>
</template>

<script>
export default {
	props: ['resourceName', 'resourceId', 'field'],

	data() {
		return {
			selectedOptions: this.field.value || []
		};
	},

	methods: {
		handleChange(event) {
			this.$emit('input', this.selectedOptions);
		}
	}
}
</script>
