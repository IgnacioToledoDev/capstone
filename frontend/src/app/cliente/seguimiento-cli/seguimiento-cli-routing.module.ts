import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SeguimientoCliPage } from './seguimiento-cli.page';

const routes: Routes = [
  {
    path: '',
    component: SeguimientoCliPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SeguimientoCliPageRoutingModule {}
