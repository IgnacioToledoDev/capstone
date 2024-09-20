import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-home-mecanico',
  templateUrl: './home-mecanico.page.html',
  styleUrls: ['./home-mecanico.page.scss'],
})
export class HomeMecanicoPage implements OnInit {

  mecanico: string = 'Joel';
  rol:string ='Mecanico';
  eventos: { nombre: string, hora: string , patente: string}[] = [
    { nombre: 'Reunión con el equipo', hora: '10:00 AM' , patente:'ABC-1234'},
    { nombre: 'Llamada con el cliente', hora: '12:00 PM' , patente:'ABC-1234'},
    { nombre: 'Almuerzo', hora: '1:00 PM', patente:'ABC-1234' }
  ];

  constructor() { }

  ngOnInit() {
  }

}
