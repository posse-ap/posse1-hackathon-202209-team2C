'use strict'
const openModalClassList = document.querySelectorAll('.modal-open')
const closeModalClassList = document.querySelectorAll('.modal-close')
const overlay = document.querySelector('.modal-overlay')
const body = document.querySelector('body')
const modal = document.querySelector('.modal')
const modalInnerHTML = document.getElementById('modalInner')

for (let i = 0; i < openModalClassList.length; i++) {
  openModalClassList[i].addEventListener('click', (e) => {
    e.preventDefault()
    let eventId = parseInt(e.currentTarget.id.replace('event-', ''))
    openModal(eventId)
  }, false)
}

for (var i = 0; i < closeModalClassList.length; i++) {
  closeModalClassList[i].addEventListener('click', closeModal)
}

overlay.addEventListener('click', closeModal)


async function openModal(eventId) {
  try {
    const url = '/api/getModalInfo.php?eventId=' + eventId
    const res = await fetch(url)
    const event = await res.json()
    let modalHTML = `
      <h2 class="text-md font-bold mb-3">${event.name}</h2>
      <p class="text-sm">${event.date}（${event.day_of_week}）</p>
      <p class="text-sm">${event.start_at} ~ ${event.end_at}</p>

      <hr class="my-4">

      <p class="text-md">
        ${event.message}
      </p>

      <hr class="my-4">

      <p class="text-sm"><span class="text-xl">${event.total_participants}</span>人参加 ></p>
    `
    switch (0) {
      case 0:
        modalHTML += `
          <div class="text-center mt-6">
            <!--
            <p class="text-lg font-bold text-yellow-400">未回答</p>
            <p class="text-xs text-yellow-400">期限 ${event.deadline}</p>
            -->
          </div>
          <div class="flex mt-5">
            <button id="join${eventId}" class="flex-1 bg-blue-500 py-2 mx-3 rounded-3xl text-white text-lg font-bold" onclick="participateEvent(${eventId})">参加する</button>
            <button id="notJoin${eventId}" class="flex-1 bg-gray-300 py-2 mx-3 rounded-3xl text-white text-lg font-bold" onclick="notParticipateEvent(${eventId})">参加しない</button>
          </div>
        `
        break;
      case 1:
        modalHTML += `
          <div class="text-center mt-10">
            <p class="text-xl font-bold text-gray-300">不参加</p>
          </div>
        `
        break;
      case 2:
        modalHTML += `
          <div class="text-center mt-10">
            <p class="text-xl font-bold text-green-400">参加</p>
          </div>
        `
        break;
    }
    modalInnerHTML.insertAdjacentHTML('afterbegin', modalHTML)
  } catch (error) {
    console.log(error)
  }
  toggleModal()
}

function closeModal() {
  modalInnerHTML.innerHTML = ''
  toggleModal()
}

function toggleModal() {
  modal.classList.toggle('opacity-0')
  modal.classList.toggle('pointer-events-none')
  body.classList.toggle('modal-active')
}

// let join = document.getElementById(`join${eventId}`);
var join = document.getElementById('join18');
console.log(join);
// →nullになっちゃう、、、、

async function participateEvent(eventId) {
  try {
    let formData = new FormData();
    formData.append('eventId', eventId);
    formData.append('attendance', 1);
    const url = '/api/postEventAttendance.php'
    await fetch(url, {
      method: 'POST',
      body: formData
    }).then((res) => {
      if(res.status !== 200) {
        throw new Error("system error");
      }
      return res.text();
    })
    closeModal()
    location.reload()
    // join.disabled = true;
    // let notJoin = document.getElementById(`notJoin${eventId}`);
    // notJoin.disabled = false;
    // join.classList.add('active');
    join.classList.toggle('pointer-events-none')

  } catch (error) {
    console.log(error)
  }
}

async function notParticipateEvent(eventId) {
  try {
    // let join = document.getElementById(`join${eventId}`);
    // join.disabled = false;
    // let notJoin = document.getElementById(`notJoin${eventId}`);
    // notJoin.disabled = true;
    let formData = new FormData();
    formData.append('eventId', eventId);
    formData.append('attendance', 0);
    const url = '/api/postEventAttendance.php'
    await fetch(url, {
      method: 'POST',
      body: formData
    }).then((res) => {
      if(res.status !== 200) {
        throw new Error("system error");
      }
      return res.text();
    })
    closeModal()
    location.reload()
    let join = document.getElementById(`join${eventId}`);
    join.disabled = false;
    let notJoin = document.getElementById(`notJoin${eventId}`);
    notJoin.disabled = true;
  } catch (error) {
    console.log(error)
  }
}
