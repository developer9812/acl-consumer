<template lang="html">
  <div class="container">
    <div class="column">
      <p>Exercise List will be rendered here</p>
      <a class="button is-info is-outlined">Verify Token</a>
      <a class="button is-success is-outlined" @click="testRepository">Test Repository</a>
    </div>
  </div>
</template>

<script>
export default {
  data: function(){
    return {
      exercises: {}
    }
  },
  created: function(){
    //call loopback for exercises list
    this.getExercise();
  },
  methods: {
    getExercise: function(){
      // axios.get('http://192.168.1.74:3000/api/exercises/')
			// 	.then(response => {
			// 		this.exercises = response.data;
			// 	})
			// 	.catch(error => {
			// 	});
			axios.get('api/token/session')
      .then(response => {
        console.log("TOKEN response");
        console.log(response.data);
      })
      .catch(error => {
        console.log("ERROR TOKEN");
        console.log(error);
      })
    },
    testRepository: function(){
      axios.get('api/repository/test')
      .then(response => {
        console.log("REPO RESPONSE");
        console.log(response.data);
      })
      .catch(error => {
        console.log("ERROR REPO");
        console.log(error);
      })
    }
  }
}
</script>

<style lang="css">
</style>
