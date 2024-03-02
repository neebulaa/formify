<template>
	<main>
		<div class="hero py-5 bg-light">
			<div class="container text-center">
				<h2 class="mb-3">Response Submission Success</h2>
				<div class="text-muted">Your response is recorded.</div>
				<router-link :to="{ name: 'home' }" class="mt-1"
					>Back to home?</router-link
				>
			</div>
		</div>
	</main>
</template>
