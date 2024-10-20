import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

interface Status {
  title: string;
  image: string;
  buttonTitle?: string;
}
@Component({
  selector: 'app-seguimiento',
  templateUrl: './seguimiento.page.html',
  styleUrls: ['./seguimiento.page.scss'],
})
export class SeguimientoPage implements OnInit {

  constructor(private navCtrl: NavController) { }

  ngOnInit() {
  }
  goBack() {
    this.navCtrl.back();
  }

  eventosinicio: { title: string, image: string ,buttonTitle?: string}[] = [
    { title: 'Iniciado', image:'assets/images/inicio.jpg',buttonTitle: 'Actual' },
  ];
  eventosprogreso: { title: string, image: string ,buttonTitle?: string}[] = [
    { title: 'En progreso', image:'assets/images/inicio.jpg',buttonTitle: 'Próximo...' },
  ];
  eventosListo: { title: string, image: string ,buttonTitle?: string}[] = [
    { title: 'Listo para entrega', image:'assets/images/inicio.jpg',buttonTitle: 'Próximo...' },
  ];


  onButtonClick(evento: Status) {
    console.log( evento.title);

  }
  presentAlert(){
    console.log('Acción Finalizar');
  }
}