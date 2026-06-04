<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="uploadForm">
        <input type="file" name="image[]" accept="image/*" multiple>
        <!-- <input type="text" name="answerId" placeholder="answer"> -->
        <!-- <input type="text" name="postId" placeholder="post"> -->
        <input type="text" name="title" placeholder="title">
        <input type="text" name="text" placeholder="text">
        <input type="text" name="category_id" placeholder="cat">
        <input type="text" name="level_id" placeholder="lvl">
        <!-- <input type="text" name="newImages" placeholder="imgName,imgName"> -->
        <!-- <input type="text" name="id" placeholder="id"> -->
        <!-- <input type="text" name="vote" placeholder="vote"> -->
        <!-- <input type="text" name="email" placeholder="email">
        <input type="text" name="name" placeholder="name">
        <input type="text" name="show_name" placeholder="show_name">
        <input type="text" name="password" placeholder="password"> -->
        <button type="submit">Submit</button>
    </form>
</body>

</html>


<script>
    document.getElementById('uploadForm').onsubmit = async (e) => {
        e.preventDefault()

        const formData = new FormData(e.target)

        console.log(Object.fromEntries(formData))

        try {
            const response = await fetch('http://404devs/create-post', {
                method: 'POST',
                body: formData
            })

            const result = await response.text()
            console.log('Ответ:', result)
        } catch (error) {
            console.error('трабл с CORS:', error)
        }
    }
</script>