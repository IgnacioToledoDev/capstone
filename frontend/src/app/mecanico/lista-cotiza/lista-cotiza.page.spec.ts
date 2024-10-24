import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ListaCotizaPage } from './lista-cotiza.page';

describe('ListaCotizaPage', () => {
  let component: ListaCotizaPage;
  let fixture: ComponentFixture<ListaCotizaPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ListaCotizaPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
