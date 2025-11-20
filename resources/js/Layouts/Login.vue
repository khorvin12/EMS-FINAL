<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <input type="email" v-model="email" placeholder="Email" required />
      <input type="password" v-model="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p>{{ message }}</p>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      message: ""
    };
  },
  methods: {
    async login() {
      try {
        const res = await axios.post("/api/login", {
          email: this.email,
          password: this.password,
        });
        this.message = "Login successful!";
      } catch (error) {
        this.message = "Invalid email or password.";
      }
    },
  },
};
</script>
