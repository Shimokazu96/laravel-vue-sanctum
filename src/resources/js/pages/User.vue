<template>
  <div class="bg-gray-100 flex-auto">
    <div class="flex justify-center mt-16">
      <div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20">
        <div>
          <h2 class="text-gray-800 text-3xl font-semibold">Design Tools</h2>
          <p>名前: {{ user.name }}</p>
          <p>メールアドレス: {{ user.email }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: "",
    };
  },
  mounted() {
    axios
      .get("/api/user")
      .then((response) => {
        console.log("ログイン済み");
        this.user = response.data;
      })
      .catch((error) => {
        console.log("ログインしてません");
        console.log(error);
      });
  },
  // methods: {
  //   logout() {
  //     axios
  //       .post("api/logout")
  //       .then((response) => {
  //         console.log(response);
  //         this.$router.push("/login");
  //       })
  //       .catch((error) => {
  //         console.log(error);
  //       });
  //   },
  // },
};
</script>
