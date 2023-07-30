<script setup>

// const onAttach = event => {
//     fileData.csv_file = event.target.files[0];
//     console.log('fileData', fileData)

//     const formData = new FormData();
//     formData.append('csv_file', fileData.csv_file);

//     axios.post('api/customers', formData, {
//           headers: {
//             // 'Access-Control-Allow-Origin': '*', 
//             'Content-Type': 'multipart/form-data' // Important! Don't forget this header
//           },
//         }).then(response => {
//         console.log("it's back: ", response)
//     });
// }


</script>
<template>
<div class="card lg:card-side bg-base-100 shadow-xl">
    <!-- <figure><img src="../assets/icons/Jobs-11.png" alt="Album" class="w-1/2  h-1/2 max-w-sm rounded-lg shadow-2xl" /></figure> -->
    <div class="card-body">
        <h2 class="card-title">Upload csv:</h2>
        <div class="card-actions justify-end">
            <form @submit.prevent>    
                <div class="form-control w-full max-w-xs">
                    <input type="file" @change="$emit('onAttach', $event)" class="file-input file-input-bordered w-full max-w-xs" />
                </div>
            </form>
        </div>
    </div>
</div>
</template>