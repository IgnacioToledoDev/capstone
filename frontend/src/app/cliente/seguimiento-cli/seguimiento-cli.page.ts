import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';
interface Status {
  title: string;
  image: string;
  buttonTitle?: string;
}
@Component({
  selector: 'app-seguimiento-cli',
  templateUrl: './seguimiento-cli.page.html',
  styleUrls: ['./seguimiento-cli.page.scss'],
})
export class SeguimientoCliPage implements OnInit {

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
    this.navCtrl.navigateForward('/cliente/home-cliente');
    console.log('Acción Finalizar');
  }
}