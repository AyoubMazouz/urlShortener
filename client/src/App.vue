<script>
  import { Icon } from '@iconify/vue';

  export default {
    data() {
      return {
        url: '',
        urlList: []
      }
    },
    components: { Icon },
    async mounted() {
      this.fetchData()
    },
    methods: {
      async fetchData() {
        const req = await fetch("http://127.0.0.1:8000/api/")

        const res = await req.json()

        if (res.ok) this.urlList = res.data
      },
      async destroyUrl(code) {
        const req = await fetch("http://127.0.0.1:8000/api/", {
          method: "DELETE",
          headers: { 'Content-Type': 'application/json', },
          body: JSON.stringify({code}),
        })

        const res = await req.json()

        if (res?.message) alert(res.message)
        if (res.ok) this.fetchData()

      },
      async submit() {
        const url = this.url
        this.url = ''
        
        const req = await fetch("http://127.0.0.1:8000/api/", {
          method: "POST",
          headers: { 'Content-Type': 'application/json', },
          body: JSON.stringify({url}),
        })

        const res = await req.json()

        if (res?.message) alert(res.message)
        if (res.ok) this.fetchData()
      }
    },
  }

</script>

<template>
  <div class="flex justify-center bg-gray-50 text-gray-800 h-[100vh]">
    <div class="max-w-[1400px] w-full pb-24 pt-6 px-4">
      
      <h1 class="text-red-600 font-bold text-xl">URL Shortener</h1>
      <h3 class="font-semibold mt-4">Challenge Number Two from FullStack Challenges SQUHR</h3>

      <form @submit.prevent="submit" class="flex gap-4 items-center mt-12">
        <input type="url" name="url" required placeholder="Past Your Url Here..." v-model="url" class="w-full focus:outline-none border-2 border-gray-400 focus:border-emerald-500 transition-all duration-300 px-2 py-2.5 rounded-lg shadow-lg">
        <button type="submit" class="rounded-lg shadow-lg py-2.5 px-6 font-bold text-lg  hover:scale-110 active:scale-90 transition-all duration-300 border-2 border-emerald-800 bg-emerald-600 hover:text-emerald-600 text-emerald-50 hover:bg-transparent">Shorten</button>
      </form>

    <div>

    <div class="overflow-hidden rounded-lg shadow-lg mt-12">
      <div class="grid grid-cols-12 gap-2 w-full border-b-2 border-emerald-800 py-2 bg-emerald-600 text-emerald-50">
        <span class="col-span-5 px-2">Original Url</span>
        <span class="col-span-5 px-2">Shortened Url</span>
        <span class="col-span-2 px-2">Visits Count</span>
      </div>
        <div v-for="(url, index) in urlList" :class="{ 'bg-gray-100': index % 2 === 0}" class="grid grid-cols-12 gap-4 w-full" >
          <a :href="url.original_url" target="_blank" class="col-span-5 px-2 py-1.5 underline hover:text-emerald-500 transition-colors duration-300 line-clamp-1">
            {{ url.original_url }}
          </a>
          <a :href="'http://127.0.0.1:8000/api/' + url.code" target="_blank" class="col-span-5 px-2 py-1.5 underline hover:text-emerald-500 transition-colors duration-300 line-clamp-1">
            http://127.0.0.1:8000/api/{{ url.code }}
          </a>
          <span class="col-span-1 px-2 py-1.5">{{ url.visits_count }}</span>
          <form @submit.prevent="destroyUrl(url.code)" class="col-span-1 px-2 py-1.5">
            <button type="submit" class="text-red-600 hover:opacity-75 hover:scale-110 active:scale-90 transition-all duration-300">
              <Icon icon="carbon:trash-can" width="22px" />
            </button>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>
