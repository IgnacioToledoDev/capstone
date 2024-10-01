import { ComponentFixture, TestBed } from '@angular/core/testing';
import { InfoSerCliPage } from './info-ser-cli.page';

describe('InfoSerCliPage', () => {
  let component: InfoSerCliPage;
  let fixture: ComponentFixture<InfoSerCliPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(InfoSerCliPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
