import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-home-mecanico',
  templateUrl: './home-mecanico.page.html',
  styleUrls: ['./home-mecanico.page.scss'],
})
export class HomeMecanicoPage implements OnInit {

  eventos: { nombre: string, hora: string , patente: string}[] = [
    { nombre: 'jose herera', hora: '10:00 AM' , patente:'ABC-0834'},
    { nombre: 'isaac bravo', hora: '12:00 PM' , patente:'AAC-8634'},
    { nombre: 'Nacho jara', hora: '1:00 PM', patente:'AHG-6434' }
  ];
  
  token: string | null = null;  
  user: any = {};              

  constructor(private userService: UserService) {}

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();

    if (sessionData) {
      this.token = sessionData.token;  
      this.user = sessionData.user;    

      console.log('Token:', this.token);
      console.log('User Info:', this.user);
    } else {
      console.log('No se encontraron datos de sesión.');
    }
  }

}
