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
    { nombre: 'jose herera', hora: '10:00 AM' , patente:'ABC-0834'},
    { nombre: 'isaac bravo', hora: '12:00 PM' , patente:'AAC-8634'},
    { nombre: 'Nacho jara', hora: '1:00 PM', patente:'AHG-6434' }
  ];

  constructor() { }

  ngOnInit() {
  }

}
