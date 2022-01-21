<template>
  <div>
    <p>名前: {{ user.name }}</p>
    <p>メールアドレス: {{ user.email }}</p>
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
        console.log('ログイン済み');
        this.user = response.data;
      })
      .catch((error) => {
        console.log('ログインしてません');
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
